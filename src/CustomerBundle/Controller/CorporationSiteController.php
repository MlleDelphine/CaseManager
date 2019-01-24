<?php

namespace CustomerBundle\Controller;

use AppBundle\Services\CSVExport;
use AppBundle\Services\CustomGridRowAction;
use AppBundle\Services\ExcelExport;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use CustomerBundle\Entity\CorporationGroup;
use CustomerBundle\Entity\CorporationSite;
use CustomerBundle\Form\CorporationSiteType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * CorporationSite controller.
 *
 */
class CorporationSiteController extends Controller
{
    /**
     * Lists all corporationSite entities.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
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
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_corporationsite", $jsonDatas, "CustomerBundle\Entity\CorporationSite");

                $error = $deserialize;
            }else{
                $error = "file_mandatory_error_msg";
            }
        }

        /*** GRID ***/
        $routeAtSubmit = $this->get("router")->generate("corporation_site_index");

        //concatenated_postal_address
        $source = new Entity("CustomerBundle:CorporationSite");
        $source->manipulateQuery(function($query){
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
        $rowAction1 = new CustomGridRowAction('modify', 'corporation_site_edit');
        $rowAction1->addRouteParameters(array('slug'));
        $rowAction1->setRouteParametersMapping(array('slug' => 'slug'));
        $rowAction1->setConfirm(true);
        $rowAction1->setConfirmMessage("Sure ?");
        $rowAction1->setTarget("_blank");
        $rowAction1->setAttributes(["class" =>"btn btn-sm btn-info"]);
        $rowAction1->setPrevIcon("fa-pencil-square-o");

//        $rowAction2 = new CustomGridRowAction('add_site', 'corporation_site_new');
//        $rowAction2->addRouteParameters(array('slug'));
//        $rowAction2->setRouteParametersMapping(array('slug' => 'slugCorpGroup'));
//        $rowAction2->setConfirm(true);
//        $rowAction2->setConfirmMessage("Sure ?");
//        $rowAction2->setTarget("_blank");
//        $rowAction2->setAttributes(["class" =>"btn btn-sm btn-primary"]);
//        $rowAction2->setPrevIcon("fa-plus");

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
           // $rowAction2,
            $rowActionContact]);
        $actionsColumn->setAlign("center");

        $grid->addColumn($actionsColumn);

        $date = date('Y-m-d H:i:s');
        $grid->addExport(new ExcelExport("Export", "[CaseManager][Customer] - Sociétés sites $date"));
        $grid->addExport(new CSVExport("Export CSV", "[CaseManager][Customer] - Sociétés sites $date"));

        $grid->setLimits(array(5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100));
        $grid->isReadyForRedirect();

        if($request->isXmlHttpRequest()){
            return $grid->getGridResponse('CustomerBundle:corporationsite:index_datatable_grid.html.twig', array('grid' => $grid, "error" => $error));
        }else{
            return $grid->getGridResponse("CustomerBundle:corporationsite:index.html.twig", array('grid' => $grid, "error" => $error));
        }
    }

    /**
     * Creates a new corporationSite entity.
     * @param Request $request
     * @param CorporationGroup $corporationGroup
     *
     * @ParamConverter("corporationGroup", class="CustomerBundle:CorporationGroup", options={"mapping": {"slugCorpGroup" : "slug"}}, isOptional="true")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, CorporationGroup $corporationGroup = null)
    {
        $corporationSite = new Corporationsite();
        if(isset($corporationGroup)){
            $corporationSite->setCorporationGroup($corporationGroup);
        }
        $form = $this->createForm(CorporationSiteType::class, $corporationSite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($corporationSite);
            $em->flush();

            return $this->redirectToRoute('corporation_site_index');
        }

        return $this->render('CustomerBundle:corporationsite:new.html.twig', array(
            'corporationSite' => $corporationSite,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a corporationSite entity.
     * @param CorporationSite $corporationSite
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(CorporationSite $corporationSite)
    {
        $deleteForm = $this->createDeleteForm($corporationSite);

        return $this->render('CustomerBundle:corporationsite:show.html.twig', array(
            'corporationSite' => $corporationSite,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing corporationSite entity.
     * @param Request          $request
     * @param CorporationSite $corporationSite
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, CorporationSite $corporationSite)
    {
        $deleteForm = $this->createDeleteForm($corporationSite);
        $editForm = $this->createForm(CorporationSiteType::class, $corporationSite);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('corporation_site_index');
        }

        return $this->render('CustomerBundle:corporationsite:edit.html.twig', array(
            'corporationSite' => $corporationSite,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a corporationSite entity.
     * @param Request          $request
     * @param CorporationSite $corporationSite
     * @return \Symfony\Component\HttpFoundation\RedirectResponse | Response
     */
    public function deleteAction(Request $request, CorporationSite $corporationSite)
    {
        $form = $this->createDeleteForm($corporationSite);

        if ($request->isXmlHttpRequest()) {
            return $this->render(":common:remove_object_modal.html.twig",
                [
                    "delete_form" => $form->createView(),
                    "object_title" => $corporationSite,
                    "default" => false
                ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($corporationSite);
            $em->flush();
        }

        return $this->redirectToRoute('corporation_site_index');
    }

    /**
     * Creates a form to delete a corporationSite entity.
     *
     * @param CorporationSite $corporationSite The corporationSite entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(CorporationSite $corporationSite)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('corporation_site_delete', array('slug' => $corporationSite->getSlug())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @param Request $request
     * @param CorporationSite $corporationSite
     * @return StreamedResponse
     */
    public function exportCorporationSiteAction(Request $request, CorporationSite $equipment){

        $response = $this->get("object.eximportdatas")->export('admin_export_corporationsite', $equipment)->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllCorporationSiteAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("admin_export_corporationsite","CustomerBundle:CorporationSite", "Corporation Sites" )->prepare($request);

        return $response;
    }
}
