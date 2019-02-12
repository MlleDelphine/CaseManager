<?php

namespace CustomerBundle\Controller;

use AppBundle\Services\CSVExport;
use AppBundle\Services\CustomGridRowAction;
use AppBundle\Services\ExcelExport;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Column\BlankColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use CustomerBundle\Entity\CorporationJobStatus;
use CustomerBundle\Form\CorporationJobStatusType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * CorporationJobStatus controller.
 *
 */
class CorporationJobStatusController extends Controller
{
    /**
     * Lists all corporationJobStatus entities.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function indexAction(Request $request)
    {
        $error = false;
        if ($request->isMethod('POST')) {

            /** @var UploadedFile $file */
            $file = $request->files->get('file');

            if($file) {

                $jsonDatas = file_get_contents($file->getRealPath());
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_corporationjobstatus", $jsonDatas, "CustomerBundle\Entity\CorporationJobStatus");

                $error = $deserialize;
            }else{
                $error = "file_mandatory_error_msg";
            }
        }

        /*** GRID ***/
        $routeAtSubmit = $this->get("router")->generate("corporation_jobstatus_index");

        //concatenated_full_name
        $source = new Entity("CustomerBundle:CorporationJobStatus", "general");
        $source->manipulateQuery(function($query){
            $query->addSelect(["GROUP_CONCAT(CONCAT_WS(' ', customerContacts.honorific, UPPER(customerContacts.lastName), customerContacts.firstName) separator ', ') as concatenated_full_name"]);
            $query->leftJoin("_a.customerContacts", "customerContacts");
            $query->groupBy("_a.id");
        });

        // Get a grid instance
        $grid = $this->get('grid');

        // Attach the source to the grid
        $grid->setSource($source);
        $grid->setRouteUrl($routeAtSubmit);
        $grid->setDefaultOrder('name', 'ASC');
        $grid->setDefaultLimit(20);

        $childButtonColumn = new BlankColumn(["id" => "child-row"]); //["id" => "child-row"]
        $childButtonColumn->manipulateRenderCell(function($value, $row, $router){
            if(!empty($row->getEntity()->getCustomerContacts()) && count($row->getEntity()->getCustomerContacts()) > 0){
                return "<span class='details-control'></span>";
            }
        });
        $childButtonColumn->setSafe(false);
        $grid->addColumn($childButtonColumn, 1);

        /***
         * ACTIONS
         */
        $rowAction1 = new CustomGridRowAction('modify', 'corporation_jobstatus_edit');
        $rowAction1->addRouteParameters(array('slug'));
        $rowAction1->setRouteParametersMapping(array('slug' => 'slug'));
        $rowAction1->setConfirm(true);
        $rowAction1->setConfirmMessage("Sure ?");
        $rowAction1->setTarget("_blank");
        $rowAction1->setAttributes(["class" =>"btn btn-sm btn-info"]);
        $rowAction1->setPrevIcon("fa-pencil-square-o");

        $actionsColumn = new ActionsColumn("actions_column", "ACTIONS", [
            $rowAction1]);
        $actionsColumn->setAlign("center");

        $grid->addColumn($actionsColumn);

        $date = date('Y-m-d H:i:s');
        $grid->addExport(new ExcelExport("Export", "[CaseManager][Customer] - Poste clients $date"));
        $grid->addExport(new CSVExport("Export CSV", "[CaseManager][Customer] - Postes clients $date"));

        $grid->setLimits(array(5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100));
        $grid->isReadyForRedirect();

        if($request->isXmlHttpRequest()){
            return $grid->getGridResponse(':customer:index_datatable_grid_customer.html.twig', array('grid' => $grid, "error" => $error, "childrenRow" => "CustomerContacts", "childrenProperties" => ["firstname_capitalize", "lastname_capitalize"], "childrenRouteName" => "corporation_jobstatus_get_children"));
        }else{
            return $grid->getGridResponse("CustomerBundle:corporationjobstatus:index.html.twig", array('grid' => $grid, "error" => $error, "childrenRow" => "CustomerContacts", "childrenProperties" => ["firstname_capitalize", "lastname_capitalize"], "childrenRouteName" => "corporation_jobstatus_get_children"));
        }
    }

    /**
     * Creates a new corporationJobStatus entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $corporationJobStatus = new CorporationJobStatus();
        $form = $this->createForm(CorporationJobStatusType::class, $corporationJobStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($corporationJobStatus);
            $em->flush();

            return $this->redirectToRoute('corporation_jobstatus_index');
        }

        return $this->render('CustomerBundle:corporationjobstatus:new.html.twig', array(
            'corporationJobStatus' => $corporationJobStatus,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a corporationJobStatus entity.
     * @param CorporationJobStatus $corporationJobStatus
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(CorporationJobStatus $corporationJobStatus)
    {
        $deleteForm = $this->createDeleteForm($corporationJobStatus);

        return $this->render('CustomerBundle:corporationjobstatus:show.html.twig', array(
            'corporationJobStatus' => $corporationJobStatus,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing corporationJobStatus entity.
     * @param Request $request
     * @param CorporationJobStatus $corporationJobStatus
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, CorporationJobStatus $corporationJobStatus)
    {
        $deleteForm = $this->createDeleteForm($corporationJobStatus);
        $editForm = $this->createForm(CorporationJobStatusType::class, $corporationJobStatus);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('corporation_jobstatus_edit', array('slug' => $corporationJobStatus->getSlug()));
        }

        return $this->render('CustomerBundle:corporationjobstatus:edit.html.twig', array(
            'corporationJobStatus' => $corporationJobStatus,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a corporationJobStatus entity.
     * @param Request $request
     * @param CorporationJobStatus $corporationJobStatus
     * @return \Symfony\Component\HttpFoundation\RedirectResponse | Response
     */
    public function deleteAction(Request $request, CorporationJobStatus $corporationJobStatus)
    {
        $form = $this->createDeleteForm($corporationJobStatus);

        if ($request->isXmlHttpRequest()) {
            return $this->render(":common:remove_object_modal.html.twig",
                [
                    "delete_form" => $form->createView(),
                    "object_title" => $corporationJobStatus,
                    "default" => false
                ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($corporationJobStatus);
            $em->flush();
        }

        return $this->redirectToRoute('corporation_jobstatus_index');
    }

    /**
     * Creates a form to delete a corporationJobStatus entity.
     *
     * @param CorporationJobStatus $corporationJobStatus The corporationJobStatus entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(CorporationJobStatus $corporationJobStatus)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('corporation_jobstatus_delete', array('slug' => $corporationJobStatus->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * @param Request              $request
     * @param CorporationJobStatus $corporationJobStatus
     * @return StreamedResponse
     */
    public function exportCorporationJobStatusAction(Request $request, CorporationJobStatus $corporationJobStatus){

        $response = $this->get("object.eximportdatas")->export('admin_export_corporationjobstatus', $corporationJobStatus)->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllCorporationJobStatusAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("admin_export_corporationjobstatus","CustomerBundle:CorporationJobStatus", "Corporation Sites" )->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @param CorporationJobStatus $corporationJobStatus
     * @param $childElement
     * @return JsonResponse
     */
    public function getChildFromParentAction(Request $request, CorporationJobStatus $corporationJobStatus, $childElement){

        $mappingFunctionName = "get$childElement";
        $childElements = $corporationJobStatus->{$mappingFunctionName}();


        $response = $this->get("object.eximportdatas")->serializeInJsonString("corpo_job_status_childrow", $childElements);

        return $response;

    }
}
