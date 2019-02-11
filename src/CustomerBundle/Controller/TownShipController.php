<?php

namespace CustomerBundle\Controller;

use AppBundle\Services\CSVExport;
use AppBundle\Services\CustomGridRowAction;
use AppBundle\Services\ExcelExport;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use CustomerBundle\Entity\TownShip;
use CustomerBundle\Form\TownShipType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Township controller.
 *
 */
class TownShipController extends Controller
{
    /**
     * Lists all townShip entities.
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
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_township", $jsonDatas, "CustomerBundle\Entity\TownShip");

                $error = $deserialize;
            }else{
                $error = "file_mandatory_error_msg";
            }
        }

        /*** GRID ***/
        $routeAtSubmit = $this->get("router")->generate("township_index");

        //concatenated_postal_address
        $source = new Entity("CustomerBundle:TownShip", "merged_address");
        $source->manipulateQuery(function($query){
            $query->addSelect(["CONCAT(postalAddress.streetNumber, ', ', postalAddress.streetName, ' ', postalAddress.postalCode, ' ', postalAddress.city) as concatenated_postal_address"])
                ->leftJoin("_a.postalAddress", "postalAddress");
        });
        // Get a grid instance
        $grid = $this->get('grid');

        // Attach the source to the grid
        $grid->setSource($source);
        $grid->setRouteUrl($routeAtSubmit);
        $grid->setDefaultOrder('name', 'ASC');
        $grid->setDefaultLimit(20);

        /***
         * ACTIONS
         */
        $rowAction1 = new CustomGridRowAction('modify', 'township_edit');
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
        $grid->addExport(new ExcelExport("Export", "[CaseManager][Customer] - Communes $date"));
        $grid->addExport(new CSVExport("Export CSV", "[CaseManager][Customer] - Communes $date"));

        $grid->setLimits(array(5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100));
        $grid->isReadyForRedirect();

        if($request->isXmlHttpRequest()){
            return $grid->getGridResponse(':customer:index_datatable_grid_customer.html.twig', array('grid' => $grid, "error" => $error));
        }else{
            return $grid->getGridResponse("CustomerBundle:township:index.html.twig", array('grid' => $grid, "error" => $error));
        }
    }

    /**
     * Creates a new townShip entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $townShip = new TownShip();
        $form = $this->createForm(TownShipType::class, $townShip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($townShip);
            $em->flush();

            return $this->redirectToRoute('township_index');
        }

        return $this->render('CustomerBundle:township:new.html.twig', array(
            'townShip' => $townShip,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a townShip entity.
     * @param TownShip $townShip
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(TownShip $townShip)
    {
        $deleteForm = $this->createDeleteForm($townShip);

        return $this->render('CustomerBundle:township:show.html.twig', array(
            'townShip' => $townShip,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing townShip entity.
     * @param Request          $request
     * @param TownShip $townShip
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, TownShip $townShip)
    {
        $deleteForm = $this->createDeleteForm($townShip);
        $editForm = $this->createForm(TownShipType::class, $townShip);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('township_index');
        }

        return $this->render('CustomerBundle:township:edit.html.twig', array(
            'townShip' => $townShip,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a townShip entity.
     * @param Request          $request
     * @param TownShip $townShip
     * @return \Symfony\Component\HttpFoundation\RedirectResponse | Response
     */
    public function deleteAction(Request $request, TownShip $townShip)
    {
        $form = $this->createDeleteForm($townShip);

        if ($request->isXmlHttpRequest()) {
            return $this->render(":common:remove_object_modal.html.twig",
                [
                    "delete_form" => $form->createView(),
                    "object_title" => $townShip,
                    "default" => false
                ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($townShip);
            $em->flush();
        }

        return $this->redirectToRoute('township_index');
    }

    /**
     * Creates a form to delete a townShip entity.
     *
     * @param TownShip $townShip The townShip entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(TownShip $townShip)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('township_delete', array('slug' => $townShip->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * @param Request $request
     * @param TownShip $equipment
     * @return StreamedResponse
     */
    public function exportTownShipAction(Request $request, TownShip $equipment){

        $response = $this->get("object.eximportdatas")->export('admin_export_township', $equipment)->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllTownShipAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("admin_export_township","CustomerBundle:TownShip", "Townships" )->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @param TownShip $townShip
     * @param $childElement
     * @return JsonResponse
     */
    public function getChildFromParentAction(Request $request, TownShip $townShip, $childElement){

        $mappingFunctionName = "get$childElement";
        $childElements = $townShip->{$mappingFunctionName}();

        $response = $this->get("object.eximportdatas")->serializeInJsonString("something_childrow", $childElements);

        return $response;
    }
}
