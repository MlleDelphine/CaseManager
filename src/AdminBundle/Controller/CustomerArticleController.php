<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\CustomerArticle;
use AdminBundle\Entity\CustomerChapter;
use AdminBundle\Form\CustomerArticleType;
use AppBundle\Services\CSVExport;
use AppBundle\Services\CustomGridRowAction;
use AppBundle\Services\ExcelExport;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Customerarticle controller.
 *
 */
class CustomerArticleController extends Controller
{
    /**
     * Lists all customerArticle entities.
     * @param Request $request
     * @return Response
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
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_customerarticle", $jsonDatas, "AdminBundle\Entity\CustomerArticle");

                $error = $deserialize;
            }else{
                $error = "file_mandatory_error_msg";
            }
        }

        $customerArticles = $em->getRepository('AdminBundle:CustomerArticle')->findAll();

        return $this->render('AdminBundle:CustomerArticle:index.html.twig', array(
            'customerArticles' => $customerArticles,
            "error" => $error
        ));
    }

    /**
     * @param Request $request
     * @param CustomerChapter|null $customerChapter
     * @return Response
     * @throws \Exception
     * @ParamConverter("customerChapter", class="AdminBundle:CustomerChapter", options={"mapping": {"slugChapter" : "slug"}}, isOptional="true" )
     */
    public function gridAction(Request $request, CustomerChapter $customerChapter = null){

        /*** GRID ***/
        $routeAtSubmit = $this->get("router")->generate("customer_article_grid", ["slugChapter" => $customerChapter->getSlug()]);

        $source = new Entity("AdminBundle:CustomerArticle");
        $tableAlias = $source->getTableAlias();
        if($customerChapter) {
            $source->manipulateQuery(function (QueryBuilder $query) use ($tableAlias, $customerChapter) {
                $query
                    ->andWhere("$tableAlias.customerChapter = ".$customerChapter->getId());
            });
        }
        $grid = $this->get('grid');

        // Attach the source to the grid
        $grid->setSource($source);
        $grid->setRouteUrl($routeAtSubmit);
        $grid->setDefaultOrder('name', 'ASC');
        $grid->setDefaultLimit(10);

        /***
         * ACTIONS
         */
        $rowActionEdit = new CustomGridRowAction('modify', 'customer_article_edit');
        $rowActionEdit->addRouteParameters(array('slug'));
        $rowActionEdit->setRouteParametersMapping(array('slug' => 'slug'));
        $rowActionEdit->setIsButton(true);
        $rowActionEdit->setAttributes(["class" =>"btn btn-sm btn-info edit-customer-article", "data-toggle" => "modal", "data-target" => "#addArticleModal" ]);
        $rowActionEdit->setPrevIcon("fa-pencil-square-o");

        $rowActionDelete = new CustomGridRowAction('delete', 'customer_article_delete');
        $rowActionDelete->addRouteParameters(array('slug'));
        $rowActionDelete->setRouteParametersMapping(array('slug' => 'slug'));
        $rowActionDelete->setIsButton(true);
        $rowActionDelete->setAttributes(["class" =>"btn btn-sm btn-danger delete-customer-article", "data-toggle" => "modal", "data-target" => "#deleteModal" ]);
        $rowActionDelete->setPrevIcon("fa-trash-o");

        $actionsColumn = new ActionsColumn("actions_column", "ACTIONS", [
            $rowActionEdit,
            $rowActionDelete
        ]);

        $actionsColumn->setAlign("center");
        $grid->addColumn($actionsColumn);

        $date = date('Y-m-d H:i:s');
        $grid->addExport(new ExcelExport("Export", "[CaseManager][CustomerArticle] - Articles ENEDIS $date"));
        $grid->addExport(new CSVExport("Export CSV", "[CaseManager][CustomerArticle] - Articles ENEDIS $date"));
        $grid->setLimits(array(5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100));
        $grid->isReadyForRedirect();

        //  {% include ":common:delete_pop_up.js.twig" with { "classEntity": "delete-customer-article", "routeName": "customer_article_delete" } %}
        if($request->isXmlHttpRequest()){
            return $grid->getGridResponse(':common:default_datatable_grid.html.twig', array(
                'grid' => $grid,
                "customerChapter" => $customerChapter,
                "deleteModals" => [
                    ["classEntity" => "delete-customer-article", "routeName" => "customer_article_delete"]
                ]
            ));
        }else{
            return $grid->getGridResponse("AdminBundle:customerarticle:index.html.twig", array('grid' => $grid));
        }
    }

    /**
     * Creates a new customerArticle entity.
     * @param Request $request
     * @param CustomerChapter $customerChapter
     *
     * @ParamConverter("customerChapter", class="AdminBundle:CustomerChapter", options={"mapping": {"slugChapter" : "slug"}}, isOptional="true" )
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request, CustomerChapter $customerChapter = null)
    {
        $customerArticle = new CustomerArticle();
        $action = $this->generateUrl("customer_article_new");
        if(isset($customerChapter)){
            $customerArticle->setCustomerChapter($customerChapter);
            $action = $this->generateUrl("customer_article_new", ["slugChapter" => $customerChapter->getSlug()]);
        }
        $form = $this->createForm(CustomerArticleType::class, $customerArticle, ["mode" => CustomerArticleType::POP_UP_MODE, "action" => $action]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($customerArticle);
            $em->flush();

            return $this->redirectToRoute('customer_serial_index', ["_fragment" => "tab_serial".$customerArticle->getCustomerChapter()->getCustomerSerial()->getId()]);
        }

        /**
         * INSIDE POP UP
         */
        if ($request->isXmlHttpRequest()) {
            return $this->render("AdminBundle:customerarticle:add_article_modal.html.twig",
                [
                    "form" => $form->createView(),
                    "object_title" => $customerArticle,
                    "default" => false,
                    "mode" => "CREATE"
                ]);
        }


        return $this->render('AdminBundle:CustomerArticle:new.html.twig', array(
            'customerArticle' => $customerArticle,
            'form' => $form->createView(),
            "mode" => "CREATE"
        ));
    }

    /**
     * Finds and displays a customerArticle entity.
     * @param CustomerArticle $customerArticle
     * @return Response
     */
    public function showAction(CustomerArticle $customerArticle)
    {
        $deleteForm = $this->createDeleteForm($customerArticle);

        return $this->render('AdminBundle:CustomerArticle:show.html.twig', array(
            'customerArticle' => $customerArticle,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing customerArticle entity.
     * @param Request $request
     * @param CustomerArticle $customerArticle
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, CustomerArticle $customerArticle)
    {
        $deleteForm = $this->createDeleteForm($customerArticle);

        if($request->isXmlHttpRequest()){
            $editForm = $this->createForm(CustomerArticleType::class, $customerArticle, ["mode" => CustomerArticleType::POP_UP_MODE, "action" => $this->generateUrl("customer_article_edit", ["slug" => $customerArticle->getSlug()])]);
        }else{
            $editForm = $this->createForm(CustomerArticleType::class, $customerArticle);
        }

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            if ($request->isXmlHttpRequest()) {
                return $this->render("AdminBundle:customerarticle:add_article_modal.html.twig",
                    [
                        "form" => $editForm->createView(),
                        "object_title" => $customerArticle,
                        "default" => false
                    ]);
            }else {
                return $this->redirectToRoute('customer_article_edit', array('slug' => $customerArticle->getSlug()));
            }
        }

        if ($request->isXmlHttpRequest()) {
            return $this->render("AdminBundle:customerarticle:add_article_modal.html.twig",
                [
                    "form" => $editForm->createView(),
                    "object_title" => $customerArticle,
                    "default" => false
                ]);
        }else{
            return $this->render('AdminBundle:customerarticle:edit.html.twig', array(
                'customerArticle' => $customerArticle,
                'form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }
    }

    /**
     * Deletes a customerArticle entity.
     * @param Request $request
     * @param CustomerArticle $customerArticle
     * @return RedirectResponse|Response
     */
    public function deleteAction(Request $request, CustomerArticle $customerArticle)
    {
        $form = $this->createDeleteForm($customerArticle);
        if ($request->isXmlHttpRequest()) {
            return $this->render(":common:remove_object_modal.html.twig",
                [
                    "delete_form" => $form->createView(),
                    "object_title" => $customerArticle,
                    "default" => false
                ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($customerArticle);
            $em->flush();
        }
        if ($request->isXmlHttpRequest()) {
            return $this->redirectToRoute('customer_article_index');
        }else{
            return $this->redirectToRoute('customer_serial_index');
        }
    }

    /**
     * Creates a form to delete a customerArticle entity.
     *
     * @param CustomerArticle $customerArticle The customerArticle entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(CustomerArticle $customerArticle)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('customer_article_delete', array('slug' => $customerArticle->getSlug())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
