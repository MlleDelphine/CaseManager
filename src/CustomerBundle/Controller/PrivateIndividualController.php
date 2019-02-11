<?php

namespace CustomerBundle\Controller;

use AppBundle\Services\CSVExport;
use AppBundle\Services\CustomGridRowAction;
use AppBundle\Services\ExcelExport;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use CustomerBundle\Entity\CustomerContact;
use CustomerBundle\Entity\PrivateIndividual;
use CustomerBundle\Form\PrivateIndividualType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Privateindividual controller.
 *
 */
class PrivateIndividualController extends Controller
{
    /**
     * Lists all privateIndividual entities.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $error = false;
        if ($request->isMethod('POST')) {

            /** @var UploadedFile $file */
            $file = $request->files->get('file');

            if($file) {

                $jsonDatas = file_get_contents($file->getRealPath());
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_privateindividual", $jsonDatas, "CustomerBundle\Entity\PrivateIndividual");

                $error = $deserialize;
            }else{
                $error = "file_mandatory_error_msg";
            }
        }

        /*** GRID ***/
        $routeAtSubmit = $this->get("router")->generate("private_individual_index");

        //concatenated_postal_address
        $source = new Entity("CustomerBundle:PrivateIndividual", "merged_address_full_name");
        $source->manipulateQuery(function($query){
            //"postalAddress.streetNumber, postalAddress.streetName, postalAddress.postalCode, postalAddress.city,
            $query->addSelect(["CONCAT(postalAddress.streetNumber, ', ', postalAddress.streetName, ' ', postalAddress.postalCode, ' ', postalAddress.city) as concatenated_postal_address"])
                ->leftJoin("_a.postalAddress", "postalAddress");
            $query->addSelect(["CONCAT(_a.honorific, ' ', UPPER(_a.lastName), ' ', _a.firstName) as concatenated_full_name"]);
        });
        // $source->s
        // Get a grid instance
        $grid = $this->get('grid');
        $grid->setColumnsOrder(["concatenated_full_name", "mailAddress", "country", "phoneNumber", "concatenated_address", "created", "updated"]);

        // Attach the source to the grid
        $grid->setSource($source);
        $grid->setRouteUrl($routeAtSubmit);
        //$grid->setDefaultOrder('name', 'ASC');
        $grid->setDefaultLimit(20);

        /***
         * ACTIONS
         */
        $rowAction1 = new CustomGridRowAction('modify', 'private_individual_edit');
        $rowAction1->addRouteParameters(array('slug'));
        $rowAction1->setRouteParametersMapping(array('slug' => 'slug'));
        $rowAction1->setConfirm(true);
        $rowAction1->setConfirmMessage("Sure ?");
        $rowAction1->setTarget("_blank");
        $rowAction1->setAttributes(["class" =>"btn btn-sm btn-info"]);
        $rowAction1->setPrevIcon("fa-pencil-square-o");

        $rowActionContact = new CustomGridRowAction('add_contact', 'customer_contact_new');
        $rowActionContact->addRouteParameters(array('slug'));
        $rowActionContact->setRouteParametersMapping(array('slug' => 'slugCustomer'));
        $rowActionContact->setConfirm(true);
        $rowActionContact->setConfirmMessage("Sure ?");
        $rowActionContact->setTarget("_blank");
        $rowActionContact->setAttributes(["class" =>"btn btn-sm btn-gold"]);
        $rowActionContact->setPrevIcon("fa-user-plus");

        $actionsColumn = new ActionsColumn("actions_column", "ACTIONS", [
            $rowAction1,
            $rowActionContact]);
        $actionsColumn->setAlign("center");

        $grid->addColumn($actionsColumn);

        $date = date('Y-m-d H:i:s');
        $grid->addExport(new ExcelExport("Export", "[CaseManager][Customer] - Particuliers $date"));
        $grid->addExport(new CSVExport("Export CSV", "[CaseManager][Customer] - Particuliers $date"));

        $grid->setLimits(array(5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100));
        $grid->isReadyForRedirect();

        if($request->isXmlHttpRequest()){
            return $grid->getGridResponse(':customer:index_datatable_grid_customer.html.twig', array('grid' => $grid, "error" => $error));
        }else{
            return $grid->getGridResponse("CustomerBundle:privateindividual:index.html.twig", array('grid' => $grid, "error" => $error));
        }
    }

    /**
     * Creates a new privateIndividual entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $privateIndividual = new Privateindividual();
        $form = $this->createForm(PrivateIndividualType::class, $privateIndividual);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($privateIndividual);
            $em->flush();

            return $this->redirectToRoute("private_individual_index");
        }

        return $this->render('CustomerBundle:privateindividual:new.html.twig', array(
            'privateIndividual' => $privateIndividual,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a privateIndividual entity.
     * @param PrivateIndividual $privateIndividual
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(PrivateIndividual $privateIndividual)
    {
        $deleteForm = $this->createDeleteForm($privateIndividual);

        return $this->render('CustomerBundle:privateindividual:show.html.twig', array(
            'privateIndividual' => $privateIndividual,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing privateIndividual entity.
     * @param Request           $request
     * @param PrivateIndividual $privateIndividual
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, PrivateIndividual $privateIndividual)
    {
        $deleteForm = $this->createDeleteForm($privateIndividual);
        $editForm = $this->createForm(PrivateIndividualType::class, $privateIndividual);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute("private_individual_index");
        }

        return $this->render('CustomerBundle:privateindividual:edit.html.twig', array(
            'privateIndividual' => $privateIndividual,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a privateIndividual entity.
     * @param Request           $request
     * @param PrivateIndividual $privateIndividual
     * @return \Symfony\Component\HttpFoundation\RedirectResponse | Response
     */
    public function deleteAction(Request $request, PrivateIndividual $privateIndividual)
    {
        $form = $this->createDeleteForm($privateIndividual);

        if ($request->isXmlHttpRequest()) {
            return $this->render(":common:remove_object_modal.html.twig",
                [
                    "delete_form" => $form->createView(),
                    "object_title" => $privateIndividual,
                    "default" => false
                ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($privateIndividual);
            $em->flush();
        }

        return $this->redirectToRoute('private_individual_index');

    }

    /**
     * Creates a form to delete a privateIndividual entity.
     *
     * @param PrivateIndividual $privateIndividual The privateIndividual entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(PrivateIndividual $privateIndividual)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('private_individual_delete', array('slug' => $privateIndividual->getSlug())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @param Request $request
     * @param PrivateIndividual $privateIndividual
     * @return StreamedResponse
     */
    public function exportPrivateIndividualAction(Request $request, PrivateIndividual $privateIndividual){

        $response = $this->get("object.eximportdatas")->export('admin_export_privateindividual', $privateIndividual)->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllPrivateIndividualAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("admin_export_privateindividual","CustomerBundle:PrivateIndividual", "Private Individuals" )->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @param PrivateIndividual $privateIndividual
     * @param $childElement
     * @return JsonResponse
     */
    public function getChildFromParentAction(Request $request, PrivateIndividual $privateIndividual, $childElement){

        $mappingFunctionName = "get$childElement";
        $childElements = $privateIndividual->{$mappingFunctionName}();

        $response = $this->get("object.eximportdatas")->serializeInJsonString("something_childrow", $childElements);

        return $response;
    }
}
