<?php

namespace BusinessBundle\Controller;

use AppBundle\Services\CSVExport;
use AppBundle\Services\CustomGridRowAction;
use AppBundle\Services\ExcelExport;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Column\BlankColumn;
use APY\DataGridBundle\Grid\Export\JSONExport;
use APY\DataGridBundle\Grid\Source\Entity;
use BusinessBundle\Entity\BusinessCase;
use BusinessBundle\Form\BusinessCaseType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * BusinessCase controller.
 *
 */
class BusinessCaseController extends Controller
{
    /**
     * Lists all businessCase entities.
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
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_business_case", $jsonDatas, "BusinessCaseBundle\Entity\BusinessCase");

                $error = $deserialize;
            }else{
                $error = "file_mandatory_error_msg";
            }
        }

        /*** GRID ***/
        $routeAtSubmit = $this->get("router")->generate("business_case_index");

        //concatenated_full_name
        $source = new Entity("BusinessBundle:BusinessCase", "general");


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
        $rowAction1 = new CustomGridRowAction('modify', 'business_case_edit');
        $rowAction1->addRouteParameters(array('slug'));
        $rowAction1->setRouteParametersMapping(array('slug' => 'slug'));
        $rowAction1->setConfirm(true);
        $rowAction1->setConfirmMessage("Sure ?");
        $rowAction1->setTarget("_blank");
        $rowAction1->setAttributes(["class" =>"btn btn-sm btn-info"]);
        $rowAction1->setPrevIcon("fa-pencil-square-o");

        $rowAction2 = new CustomGridRowAction('export', 'business_case_export');
        $rowAction2->addRouteParameters(array('slug'));
        $rowAction2->setRouteParametersMapping(array('slug' => 'slug'));
        $rowAction2->setAttributes(["class" =>"btn btn-sm btn-warning"]);
        $rowAction2->setPrevIcon("fa-download");

        $rowAction3 = new CustomGridRowAction('delete', 'business_case_delete');
        $rowAction3->setIsButton(true);
        $rowAction3->addRouteParameters(array('slug'));
        $rowAction3->setRouteParametersMapping(array('slug' => 'slug'));
        $rowAction3->setAttributes(["class" => "btn btn-sm btn-danger delete-entity", "data-toggle" => "modal", "data-target" => "#deleteModal", "data-slug" => "slug"]);
        $rowAction3->setPrevIcon("fa-download");

        $actionsColumn = new ActionsColumn("actions_column", "ACTIONS", [
            $rowAction1,
            $rowAction2,
            $rowAction3]);
        $actionsColumn->setAlign("center");

        $grid->addColumn($actionsColumn);

        $date = date('Y-m-d H:i:s');
        $grid->addExport(new ExcelExport("Export Excel", "[CaseManager][BusinessCase] - Dossiers d'affaire - $date"));
        $grid->addExport(new CSVExport("Export CSV", "[CaseManager][BusinessCase] - Dossiers d'affaire - $date"));
        $grid->addExport(new JSONExport("Export JSON", "[CaseManager][BusinessCase] - Dossiers d'affaire - $date"));

        $grid->setLimits(array(5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100));
        $grid->isReadyForRedirect();

        $returnParams = [
            "grid" => $grid,
            "error" => $error,
            "childrenRow" => "Media",
            "childrenProperties" => ["name_capitalize"],
            "childrenRouteName" => "business_case_get_children"
        ];

        if($request->isXmlHttpRequest()){
            return $grid->getGridResponse(':common:index_datatable_grid_common.html.twig', $returnParams);
        }else{
            return $grid->getGridResponse("BusinessBundle:businesscase:index.html.twig", $returnParams);
        }

//
//
//
//
//        $businessCases = $em->getRepository('BusinessBundle:BusinessCase')->findAll();
//
//        return $this->render('BusinessBundle:businesscase:index.html.twig', array(
//            'businessCases' => $businessCases,
//            "error" => $error
//        ));
    }

    /**
     * Creates a new businessCase entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $businessCase = new BusinessCase();
        $form = $this->createForm(BusinessCaseType::class, $businessCase);
        $form->handleRequest($request);

        if($request->isXmlHttpRequest()){
            return $this->render("BusinessBundle:businesscase:business_case_form.html.twig",['businessCase' => $businessCase,
                'form' => $form->createView()]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($businessCase);
            $em->flush();

            return $this->redirectToRoute('business_case_index');
        }

        return $this->render('BusinessBundle:businesscase:new.html.twig', array(
            'businessCase' => $businessCase,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a businessCase entity.
     * @param BusinessCase $businessCase
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(BusinessCase $businessCase)
    {
        $deleteForm = $this->createDeleteForm($businessCase);

        return $this->render('BusinessBundle:businesscase:show.html.twig', array(
            'businessCase' => $businessCase,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing businessCase entity.
     * @param Request          $request
     * @param BusinessCase $businessCase
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, BusinessCase $businessCase)
    {
        $deleteForm = $this->createDeleteForm($businessCase);
        $editForm = $this->createForm(BusinessCaseType::class, $businessCase);
        $editForm->handleRequest($request);

        if($request->isXmlHttpRequest()){
            return $this->render("BusinessBundle:businesscase:business_case_form.html.twig",['businessCase' => $businessCase,
                'form' => $editForm->createView()]);
        }

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('business_case_index');
        }


        return $this->render('BusinessBundle:businesscase:edit.html.twig', array(
            'businessCase' => $businessCase,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a businessCase entity.
     * @param Request          $request
     * @param BusinessCase $businessCase
     * @return \Symfony\Component\HttpFoundation\RedirectResponse | Response
     */
    public function deleteAction(Request $request, BusinessCase $businessCase)
    {
        $form = $this->createDeleteForm($businessCase);

        if ($request->isXmlHttpRequest()) {
            return $this->render(":common:remove_object_modal.html.twig",
                [
                    "delete_form" => $form->createView(),
                    "object_title" => $businessCase,
                    "default" => false
                ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($businessCase);
            $em->flush();
        }

        return $this->redirectToRoute('business_case_index');
    }

    /**
     * Creates a form to delete a businessCase entity.
     *
     * @param BusinessCase $businessCase The businessCase entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(BusinessCase $businessCase)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('business_case_delete', array('slug' => $businessCase->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * @param Request $request
     * @param BusinessCase $businessCase
     * @return StreamedResponse
     */
    public function exportBusinessCaseAction(Request $request, BusinessCase $businessCase){

        $response = $this->get("object.eximportdatas")
            ->export('admin_export_business_case', $businessCase)
            ->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllBusinessCaseAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("admin_export_business_case","BusinessCaseBundle:BusinessCase", "Business Cases" )->prepare($request);

        return $response;
    }
}
