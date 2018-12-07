<?php

namespace CustomerBundle\Controller;

use AppBundle\Services\CSVExport;
use AppBundle\Services\CustomGridRowAction;
use AppBundle\Services\ExcelExport;
use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Column\JoinColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use CustomerBundle\Entity\CorporationGroup;
use CustomerBundle\Form\CorporationGroupType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CorporationGroup controller.
 *
 */
class CorporationGroupController extends Controller
{
    /**
     * Lists all corporationGroup entities.
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
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_corporationgroup", $jsonDatas, "CustomerBundle\Entity\CorporationGroup");

                $error = $deserialize;
            }else{
                $error = "file_mandatory_error_msg";
            }
        }

        /*** GRID ***/
        $routeAtSubmit = $this->get("router")->generate("corporation_group_index");

        //concatenated_postal_address
        $source = new Entity("CustomerBundle:CorporationGroup");
        $source->manipulateQuery(function($query){
            //"postalAddress.streetNumber, postalAddress.streetName, postalAddress.postalCode, postalAddress.city,
            $query->addSelect(["CONCAT(postalAddress.streetNumber, ', ', postalAddress.streetName, ' ', postalAddress.postalCode, ' ', postalAddress.city) as concatenated_postal_address"])
                ->leftJoin("_a.postalAddress", "postalAddress");
        });
       // $source->s
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
        $rowAction1 = new CustomGridRowAction('modify', 'corporation_group_edit');
        $rowAction1->addRouteParameters(array('slug'));
        $rowAction1->setRouteParametersMapping(array('slug' => 'slug'));
        $rowAction1->setConfirm(true);
        $rowAction1->setConfirmMessage("Sure ?");
        $rowAction1->setTarget("_blank");
        $rowAction1->setAttributes(["class" =>"btn btn-sm btn-info"]);
        $rowAction1->setPrevIcon("fa-pencil-square-o");

        $rowAction2 = new CustomGridRowAction('add_site', 'corporation_site_new');
        $rowAction2->addRouteParameters(array('slug'));
        $rowAction2->setRouteParametersMapping(array('slug' => 'slugCorpGroup'));
        $rowAction2->setConfirm(true);
        $rowAction2->setConfirmMessage("Sure ?");
        $rowAction2->setTarget("_blank");
        $rowAction2->setAttributes(["class" =>"btn btn-sm btn-primary"]);
        $rowAction2->setPrevIcon("fa-plus");

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
            $rowAction2,
            $rowActionContact]);
        $actionsColumn->setAlign("center");

        $grid->addColumn($actionsColumn);

        $date = date('Y-m-d H:i:s');
        $grid->addExport(new ExcelExport("Export", "[CaseManager][Customer] - Sociétés groupes $date"));
        $grid->addExport(new CSVExport("Export CSV", "[CaseManager][Customer] - Sociétés groupes $date"));

        $grid->setLimits(array(5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100));
        $grid->isReadyForRedirect();

        if($request->isXmlHttpRequest()){
            return $grid->getGridResponse('CustomerBundle:corporationgroup:index_datatable_grid.html.twig', array('grid' => $grid, "error" => $error));
        }else{
            return $grid->getGridResponse("CustomerBundle:corporationgroup:index.html.twig", array('grid' => $grid, "error" => $error));
        }

        /****/

        $corporationGroups = $em->getRepository('CustomerBundle:CorporationGroup')->findAll();

        return $this->render('CustomerBundle:corporationgroup:index.html.twig', array(
            'corporationGroups' => $corporationGroups,
            "error" => $error
        ));
    }

    /**
     * Creates a new corporationGroup entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $corporationGroup = new CorporationGroup();
        $form = $this->createForm(CorporationGroupType::class, $corporationGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($corporationGroup);
            $em->flush();

            return $this->redirectToRoute('corporation_group_index');
        }

        return $this->render('CustomerBundle:corporationgroup:new.html.twig', array(
            'corporationGroup' => $corporationGroup,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a corporationGroup entity.
     * @param CorporationGroup $corporationGroup
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(CorporationGroup $corporationGroup)
    {
        $deleteForm = $this->createDeleteForm($corporationGroup);

        return $this->render('CustomerBundle:corporationgroup:show.html.twig', array(
            'corporationGroup' => $corporationGroup,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing corporationGroup entity.
     * @param Request          $request
     * @param CorporationGroup $corporationGroup
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, CorporationGroup $corporationGroup)
    {
        $deleteForm = $this->createDeleteForm($corporationGroup);
        $editForm = $this->createForm(CorporationGroupType::class, $corporationGroup);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('corporation_group_index');
        }

        return $this->render('CustomerBundle:corporationgroup:edit.html.twig', array(
            'corporationGroup' => $corporationGroup,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a corporationGroup entity.
     * @param Request          $request
     * @param CorporationGroup $corporationGroup
     * @return \Symfony\Component\HttpFoundation\RedirectResponse | Response
     */
    public function deleteAction(Request $request, CorporationGroup $corporationGroup)
    {
        $form = $this->createDeleteForm($corporationGroup);

        if ($request->isXmlHttpRequest()) {
            return $this->render(":common:remove_object_modal.html.twig",
                [
                    "delete_form" => $form->createView(),
                    "object_title" => $corporationGroup,
                    "default" => false
                ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($corporationGroup);
            $em->flush();
        }

        return $this->redirectToRoute('corporation_group_index');
    }

    /**
     * Creates a form to delete a corporationGroup entity.
     *
     * @param CorporationGroup $corporationGroup The corporationGroup entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(CorporationGroup $corporationGroup)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('corporation_group_delete', array('slug' => $corporationGroup->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * @param Request $request
     * @param CorporationGroup $equipment
     * @return StreamedResponse
     */
    public function exportCorporationGroupAction(Request $request, CorporationGroup $equipment){

        $response = $this->get("object.eximportdatas")->export('admin_export_corporationgroup', $equipment)->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllCorporationGroupAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("admin_export_corporationgroup","CustomerBundle:CorporationGroup", "Corporation Groups" )->prepare($request);

        return $response;
    }
}
