<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Resource;
use AppBundle\Form\ResourceType;
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
 * Resource controller.
 *
 */
class ResourceController extends Controller
{
    /**
     * Lists all resource entities.
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
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_resource", $jsonDatas, "AppBundle\Entity\Resource");

                $error = $deserialize;
            }else{
                $error = "file_mandatory_error_msg";
            }
        }

        /*** GRID ***/
        $routeAtSubmit = $this->get("router")->generate("resource_index");
        $source = new Entity("AppBundle:Resource", "general");

        // Get a grid instance
        $grid = $this->get('grid');

        // Attach the source to the grid
        $grid->setSource($source);
        $grid->setRouteUrl($routeAtSubmit);
        $grid->setDefaultOrder('name', 'ASC');
        $grid->setDefaultLimit(20);

        $childButtonColumn = new BlankColumn(["id" => "child-row"]); //["id" => "child-row"]
        $childButtonColumn->manipulateRenderCell(function($value, $row, $router){
            if(!empty($row->getEntity()->getTimePrices()) && count($row->getEntity()->getTimePrices()) > 0){
                return "<span class='details-control'></span>";
            }
        });
        $childButtonColumn->setSafe(false);
        $grid->addColumn($childButtonColumn, 1);

        /***
         * ACTIONS
         */
        $rowAction1 = new CustomGridRowAction('modify', 'resource_edit');
        $rowAction1->addRouteParameters(array('slug'));
        $rowAction1->setRouteParametersMapping(array('slug' => 'slug'));
        $rowAction1->setConfirm(true);
        $rowAction1->setConfirmMessage("Sure ?");
        $rowAction1->setTarget("_blank");
        $rowAction1->setAttributes(["class" =>"btn btn-sm btn-info"]);
        $rowAction1->setPrevIcon("fa-pencil-square-o");

        $rowAction2 = new CustomGridRowAction('export', 'resource_export');
        $rowAction2->addRouteParameters(array('slug'));
        $rowAction2->setRouteParametersMapping(array('slug' => 'slug'));
        $rowAction2->setAttributes(["class" =>"btn btn-sm btn-warning"]);
        $rowAction2->setPrevIcon("fa-download");

        $rowAction3 = new CustomGridRowAction('delete', 'resource_delete');
        $rowAction3->addRouteParameters(array('slug'));
        $rowAction3->setIsButton(true);
        $rowAction3->setRouteParametersMapping(array('slug' => 'slug'));
        $rowAction3->setAttributes(["class" => "btn btn-sm btn-danger delete-entity", "data-toggle" => "modal", "data-target" => "#deleteModal"]);
        $rowAction3->setPrevIcon("fa-download");

        $actionsColumn = new ActionsColumn("actions_column", "ACTIONS", [
            $rowAction1,
            $rowAction2,
            $rowAction3]);
        $actionsColumn->setAlign("center");

        $grid->addColumn($actionsColumn);

        $date = date('Y-m-d H:i:s');
        $grid->addExport(new ExcelExport("Export", "[CaseManager][Resource] - Ressource - $date"));
        $grid->addExport(new CSVExport("Export CSV", "[CaseManager][Resource] - Ressource - $date"));
        $grid->addExport(new JSONExport("Export JSON", "[CaseManager][Resource] - Ressource - $date"));

        $grid->setLimits(array(5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100));
        $grid->isReadyForRedirect();

        $returnParams = [
            "grid" => $grid,
            "error" => $error,
            "childrenRow" => "TimePrices",
            "childrenProperties" => ["unitary_price_capitalize", "from_m_capitalize", "until_cod_capitalize"],
            "childrenRouteName" => "resource_get_children"
        ];

        if($request->isXmlHttpRequest()){
            return $grid->getGridResponse(':common:index_datatable_grid_common.html.twig', $returnParams);
        }else{
            return $grid->getGridResponse("AppBundle:resource:index.html.twig", $returnParams);
        }
    }

    /**
     * Creates a new resource entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $resource = new Resource();
        $form = $this->createForm(ResourceType::class, $resource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($resource);
            $em->flush();

            return $this->redirectToRoute('resource_index');
        }

        return $this->render('AppBundle:resource:new.html.twig', array(
            'resource' => $resource,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a resource entity.
     * @param Resource $resource
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Resource $resource)
    {
        $deleteForm = $this->createDeleteForm($resource);

        return $this->render('AppBundle:resource:show.html.twig', array(
            'resource' => $resource,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing resource entity.
     * @param Request $request
     * @param Resource $resource
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Resource $resource)
    {
        $deleteForm = $this->createDeleteForm($resource);
        $editForm = $this->createForm(ResourceType::class, $resource, ["MODE_CREATE" => false]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('resource_index');
        }

        return $this->render('AppBundle:resource:edit.html.twig', array(
            'resource' => $resource,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a resource entity.
     * @param Request $request
     * @param Resource $resource
     * @return \Symfony\Component\HttpFoundation\RedirectResponse | Response
     */
    public function deleteAction(Request $request, Resource $resource)
    {
        $form = $this->createDeleteForm($resource);

        if ($request->isXmlHttpRequest()) {
            return $this->render(":common:remove_object_modal.html.twig",
                [
                    "delete_form" => $form->createView(),
                    "object_title" => $resource,
                    "default" => false
                ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($resource);
            $em->flush();
        }

        return $this->redirectToRoute('resource_index');
    }

    /**
     * Creates a form to delete a resource entity.
     *
     * @param Resource $resource The resource entity
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(Resource $resource)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('resource_delete', array('slug' => $resource->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @param Request $request
     * @param Resource $resource
     * @return StreamedResponse
     */
    public function exportResourceAction(Request $request, Resource $resource){

        $response = $this->get("object.eximportdatas")->export('admin_export_resource', $resource)->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllResourceAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("admin_export_resource","AppBundle:Resource", "Resources" )->prepare($request);

        return $response;
    }


    /**
     * @param Request $request
     * @param Resource $resource
     * @param $childElement
     * @return JsonResponse
     */
    public function getChildFromParentAction(Request $request, Resource $resource, $childElement){

        $mappingFunctionName = "get$childElement";
        $childElements = $resource->{$mappingFunctionName}();

        $response = $this->get("object.eximportdatas")->serializeInJsonString("time_prices_as_childrow", $childElements);

        return $response;
    }
}
