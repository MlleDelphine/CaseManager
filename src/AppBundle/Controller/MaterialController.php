<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Material;
use AppBundle\Form\MaterialType;
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
 * Material controller.
 *
 */
class MaterialController extends Controller
{
    /**
     * Lists all material entities.
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
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_material", $jsonDatas, "AppBundle\Entity\Material");

                $error = $deserialize;
            }else{
                $error = "file_mandatory_error_msg";
            }
        }

        /*** GRID ***/
        $routeAtSubmit = $this->get("router")->generate("material_index");
        $source = new Entity("AppBundle:Material", "general");

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
        $rowAction1 = new CustomGridRowAction('modify', 'material_edit');
        $rowAction1->addRouteParameters(array('slug'));
        $rowAction1->setRouteParametersMapping(array('slug' => 'slug'));
        $rowAction1->setConfirm(true);
        $rowAction1->setConfirmMessage("Sure ?");
        $rowAction1->setTarget("_blank");
        $rowAction1->setAttributes(["class" =>"btn btn-sm btn-info"]);
        $rowAction1->setPrevIcon("fa-pencil-square-o");

        $rowAction2 = new CustomGridRowAction('export', 'material_export');
        $rowAction2->addRouteParameters(array('slug'));
        $rowAction2->setRouteParametersMapping(array('slug' => 'slug'));
        $rowAction2->setAttributes(["class" =>"btn btn-sm btn-warning"]);
        $rowAction2->setPrevIcon("fa-download");

        $rowAction3 = new CustomGridRowAction('delete', 'material_delete');
        $rowAction3->addRouteParameters(array('slug'));
        $rowAction3->setIsButton(true);
        $rowAction3->setRouteParametersMapping(array('slug' => 'slug'));
        $rowAction3->setAttributes(["class" => "btn btn-sm btn-danger delete-entity", "data-toggle" => "modal", "data-target" => "#deleteModal", "data-slug" => "slug"]);
        $rowAction3->setPrevIcon("fa-trash");

        $actionsColumn = new ActionsColumn("actions_column", "ACTIONS", [
            $rowAction1,
            $rowAction2,
            $rowAction3]);
        $actionsColumn->setAlign("center");

        $grid->addColumn($actionsColumn);

        $date = date('Y-m-d H:i:s');
        $grid->addExport(new ExcelExport("Export", "[CaseManager][Material] - Matériaux - $date"));
        $grid->addExport(new CSVExport("Export CSV", "[CaseManager][Material] - Matériaux - $date"));
        $grid->addExport(new JSONExport("Export JSON", "[CaseManager][Material] - Matériaux - $date"));

        $grid->setLimits(array(5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100));
        $grid->isReadyForRedirect();

        $returnParams = [
            "grid" => $grid,
            "error" => $error,
            "childrenRow" => "TimePrices",
            "childrenProperties" => ["unitary_price_capitalize", "from_m_capitalize", "until_cod_capitalize"],
            "childrenRouteName" => "material_get_children"
        ];

        if($request->isXmlHttpRequest()){
            return $grid->getGridResponse(':common:index_datatable_grid_common.html.twig', $returnParams);
        }else{
            return $grid->getGridResponse("AppBundle:material:index.html.twig", $returnParams);
        }
    }

    /**
     * Creates a new material entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $material = new Material();
        $form = $this->createForm(MaterialType::class, $material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($material);
            $em->flush();

            return $this->redirectToRoute('material_index');
        }

        return $this->render('AppBundle:material:new.html.twig', array(
            'material' => $material,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a material entity.
     * @param Material $material
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Material $material)
    {
        $deleteForm = $this->createDeleteForm($material);

        return $this->render('AppBundle:material:show.html.twig', array(
            'material' => $material,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing material entity.
     * @param Request $request
     * @param Material $material
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Material $material)
    {
        $deleteForm = $this->createDeleteForm($material);
        $editForm = $this->createForm(MaterialType::class, $material, ["MODE_CREATE" => false]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('material_index');
        }

        return $this->render('AppBundle:material:edit.html.twig', array(
            'material' => $material,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a material entity.
     * @param Request $request
     * @param Material $material
     * @return \Symfony\Component\HttpFoundation\RedirectResponse |Response
     */
    public function deleteAction(Request $request, Material $material)
    {
        $form = $this->createDeleteForm($material);

        if ($request->isXmlHttpRequest()) {
            return $this->render(":common:remove_object_modal.html.twig",
                [
                    "delete_form" => $form->createView(),
                    "object_title" => $material,
                    "default" => false
                ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($material);
            $em->flush();
        }

        return $this->redirectToRoute('material_index');
    }

    /**
     * Creates a form to delete a material entity.
     *
     * @param Material $material The material entity
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(Material $material)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('material_delete', array('slug' => $material->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @param Request $request
     * @param Material $material
     * @return StreamedResponse
     */
    public function exportMaterialAction(Request $request, Material $material){

        $response = $this->get("object.eximportdatas")->export('admin_export_material', $material)->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllMaterialAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("admin_export_material","AppBundle:Material", "Materials" )->prepare($request);

        return $response;
    }


    /**
     * @param Request $request
     * @param Material $material
     * @param $childElement
     * @return JsonResponse
     */
    public function getChildFromParentAction(Request $request, Material $material, $childElement){

        $mappingFunctionName = "get$childElement";
        $childElements = $material->{$mappingFunctionName}();

        $response = $this->get("object.eximportdatas")->serializeInJsonString("time_prices_as_childrow", $childElements);

        return $response;
    }
}
