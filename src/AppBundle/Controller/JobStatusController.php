<?php

namespace AppBundle\Controller;

use AppBundle\Entity\JobStatus;
use AppBundle\Form\JobStatusType;
use AppBundle\Services\CSVExport;
use AppBundle\Services\CustomGridRowAction;
use AppBundle\Services\ExcelExport;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Column\BlankColumn;
use APY\DataGridBundle\Grid\Export\JSONExport;
use APY\DataGridBundle\Grid\Source\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Jobstatus controller.
 *
 */
class JobStatusController extends Controller
{
    /**
     * Lists all jobStatus entities.
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
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_jobstatus", $jsonDatas, "AppBundle\Entity\JobStatus");

                $error = $deserialize;
            }else{
                $error = "file_mandatory_error_msg";
            }
        }

        /*** GRID ***/
        $routeAtSubmit = $this->get("router")->generate("jobstatus_index");
        $source = new Entity("AppBundle:JobStatus", "general");

        // Get a grid instance
        $grid = $this->get('grid');

        // Attach the source to the grid
        $grid->setSource($source);
        $grid->setRouteUrl($routeAtSubmit);
        $grid->setDefaultOrder('name', 'ASC');
        $grid->setDefaultLimit(20);

        $childButtonColumn = new BlankColumn(["id" => "child-row"]); //["id" => "child-row"]
        $childButtonColumn->manipulateRenderCell(function($value, $row, $router){
            if(!empty($row->getEntity()->getUsers()) && count($row->getEntity()->getUsers()) > 0){
                return "<span class='details-control'></span>";
            }
        });
        $childButtonColumn->setSafe(false);
        $grid->addColumn($childButtonColumn, 1);

        /***
         * ACTIONS
         */
        $rowAction1 = new CustomGridRowAction('modify', 'jobstatus_edit');
        $rowAction1->addRouteParameters(array('slug'));
        $rowAction1->setRouteParametersMapping(array('slug' => 'slug'));
        $rowAction1->setConfirm(true);
        $rowAction1->setConfirmMessage("Sure ?");
        $rowAction1->setTarget("_blank");
        $rowAction1->setAttributes(["class" =>"btn btn-sm btn-info"]);
        $rowAction1->setPrevIcon("fa-pencil-square-o");

        $rowAction2 = new CustomGridRowAction('export', 'jobstatus_export');
        $rowAction2->addRouteParameters(array('slug'));
        $rowAction2->setRouteParametersMapping(array('slug' => 'slug'));
        $rowAction2->setAttributes(["class" =>"btn btn-sm btn-warning"]);
        $rowAction2->setPrevIcon("fa-download");

        $rowAction3 = new CustomGridRowAction('delete', 'jobstatus_delete');
        $rowAction3->addRouteParameters(array('slug'));
        $rowAction3->setIsButton(true);
        $rowAction3->setRouteParametersMapping(array('slug' => 'slug'));
        $rowAction3->setAttributes(["class" => "btn btn-sm btn-danger delete-entity", "data-toggle" => "modal", "data-target" => "#deleteModal"]);
        $rowAction3->setPrevIcon("fa-trash");

        $actionsColumn = new ActionsColumn("actions_column", "ACTIONS", [
            $rowAction1,
            $rowAction2,
            $rowAction3]);
        $actionsColumn->setAlign("center");

        $grid->addColumn($actionsColumn);

        $date = date('Y-m-d H:i:s');
        $grid->addExport(new ExcelExport("Export", "[CaseManager][User] - Postes internes - $date"));
        $grid->addExport(new CSVExport("Export CSV", "[CaseManager][User] - Postes internes - $date"));
        $grid->addExport(new JSONExport("Export JSON", "[CaseManager][User] - Postes internes - $date"));

        $grid->setLimits(array(5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100));
        $grid->isReadyForRedirect();

        $returnParams = [
            "grid" => $grid,
            "error" => $error,
            "childrenRow" => "Users",
            "childrenProperties" => ["firstname_capitalize", "lastname_capitalize"],
            "childrenRouteName" => "jobstatus_get_children"
        ];

        if($request->isXmlHttpRequest()){
            return $grid->getGridResponse(':common:index_datatable_grid_common.html.twig', $returnParams);
        }else{
            return $grid->getGridResponse("AppBundle:jobstatus:index.html.twig", $returnParams);
        }
    }

    /**
     * Creates a new jobStatus entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $jobStatus = new JobStatus();
        $form = $this->createForm(JobStatusType::class, $jobStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($jobStatus);
            $em->flush();

            return $this->redirectToRoute('jobstatus_index');
        }

        return $this->render('AppBundle:jobstatus:new.html.twig', array(
            'jobStatus' => $jobStatus,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a jobStatus entity.
     * @param JobStatus $jobStatus
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(JobStatus $jobStatus)
    {
        $deleteForm = $this->createDeleteForm($jobStatus);

        return $this->render('AppBundle:jobstatus:show.html.twig', array(
            'jobStatus' => $jobStatus,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing jobStatus entity.
     * @param Request $request
     * @param JobStatus $jobStatus
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, JobStatus $jobStatus)
    {
        $deleteForm = $this->createDeleteForm($jobStatus);
        $editForm = $this->createForm(JobStatusType::class, $jobStatus);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('jobstatus_index');
        }

        return $this->render('AppBundle:jobstatus:edit.html.twig', array(
            'jobStatus' => $jobStatus,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a jobStatus entity.
     * @param Request $request
     * @param JobStatus $jobStatus
     * @return \Symfony\Component\HttpFoundation\RedirectResponse | Response
     */
    public function deleteAction(Request $request, JobStatus $jobStatus)
    {
        $form = $this->createDeleteForm($jobStatus);

        if ($request->isXmlHttpRequest()) {
            return $this->render(":common:remove_object_modal.html.twig",
                [
                    "delete_form" => $form->createView(),
                    "object_title" => $jobStatus,
                    "default" => false
                ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($jobStatus);
            $em->flush();
        }

        return $this->redirectToRoute('jobstatus_index');
    }

    /**
     * Creates a form to delete a jobStatus entity.
     *
     * @param JobStatus $jobStatus The jobStatus entity
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(JobStatus $jobStatus)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('jobstatus_delete', array('slug' => $jobStatus->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * @param Request $request
     * @param JobStatus $jobStatus
     * @return StreamedResponse
     */
    public function exportJobStatusAction(Request $request, JobStatus $jobStatus){

        $response = $this->get("object.eximportdatas")->export('admin_export_jobstatus', $jobStatus)->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllJobStatusAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("admin_export_jobstatus","AppBundle:JobStatus", "JobStatuses" )->prepare($request);

        return $response;
    }


    /**
     * @param Request $request
     * @param JobStatus $jobStatus
     * @param $childElement
     * @return JsonResponse
     */
    public function getChildFromParentAction(Request $request, JobStatus $jobStatus, $childElement){

        $mappingFunctionName = "get$childElement";
        $childElements = $jobStatus->{$mappingFunctionName}();

        $response = $this->get("object.eximportdatas")->serializeInJsonString("user_as_childrow", $childElements);

        return $response;
    }
}
