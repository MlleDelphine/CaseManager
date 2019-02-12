<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Team;
use AppBundle\Form\TeamType;
use AppBundle\Services\CSVExport;
use AppBundle\Services\CustomGridRowAction;
use AppBundle\Services\ExcelExport;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Column\BlankColumn;
use APY\DataGridBundle\Grid\Export\JSONExport;
use APY\DataGridBundle\Grid\Source\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Team controller.
 *
 */
class TeamController extends Controller
{
    /**
     * Lists all team entities.
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
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_team", $jsonDatas, "AppBundle\Entity\Team");

                $error = $deserialize;
            }else{
                $error = "file_mandatory_error_msg";
            }
        }

        /*** GRID ***/
        $routeAtSubmit = $this->get("router")->generate("team_index");
        $source = new Entity("AppBundle:Team", "general");

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
        $rowAction1 = new CustomGridRowAction('modify', 'team_edit');
        $rowAction1->addRouteParameters(array('slug'));
        $rowAction1->setRouteParametersMapping(array('slug' => 'slug'));
        $rowAction1->setConfirm(true);
        $rowAction1->setConfirmMessage("Sure ?");
        $rowAction1->setTarget("_blank");
        $rowAction1->setAttributes(["class" =>"btn btn-sm btn-info"]);
        $rowAction1->setPrevIcon("fa-pencil-square-o");

        $rowAction2 = new CustomGridRowAction('export', 'team_export');
        $rowAction2->addRouteParameters(array('slug'));
        $rowAction2->setRouteParametersMapping(array('slug' => 'slug'));
        $rowAction2->setAttributes(["class" =>"btn btn-sm btn-warning"]);
        $rowAction2->setPrevIcon("fa-download");

        $rowAction3 = new CustomGridRowAction('delete', 'team_delete');
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
        $grid->addExport(new ExcelExport("Export", "[CaseManager][Team] - Equipes - $date"));
        $grid->addExport(new CSVExport("Export CSV", "[CaseManager][Team] - Equipes - $date"));
        $grid->addExport(new JSONExport("Export JSON", "[CaseManager][Team] - Equipes - $date"));

        $grid->setLimits(array(5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100));
        $grid->isReadyForRedirect();

        $returnParams = [
            "grid" => $grid,
            "error" => $error,
            "childrenRow" => "Users",
            "childrenProperties" => ["firstname_capitalize", "lastname_capitalize"],
            "childrenRouteName" => "team_get_children"
        ];

        if($request->isXmlHttpRequest()){
            return $grid->getGridResponse(':common:index_datatable_grid_common.html.twig', $returnParams);
        }else{
            return $grid->getGridResponse("AppBundle:team:index.html.twig", $returnParams);
        }
    }

    /**
     * Creates a new team entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $team = new Team();
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $users = $form->getData()->getUsers();
            //If user submitted not contained in original users array, add it
            foreach ($users as $userAdd){
                $team->addUser($userAdd);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($team);
            $em->flush();

            return $this->redirectToRoute('team_index');
        }

        return $this->render('AppBundle:team:new.html.twig', array(
            'team' => $team,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a team entity.
     * @param Team $team
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Team $team)
    {
        $deleteForm = $this->createDeleteForm($team);

        return $this->render('AppBundle:team:show.html.twig', array(
            'team' => $team,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing team entity.
     * @param Request $request
     * @param Team $team
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Team $team)
    {
        $deleteForm = $this->createDeleteForm($team);
        $editForm = $this->createForm(TeamType::class, $team);
        if (!$team) {
            throw $this->createNotFoundException('No team found for given slug');
        }

        $originalUsers = new ArrayCollection();

        // Create an ArrayCollection of the current User objects in the database
        foreach ($team->getUsers() as $user) {
            $originalUsers->add($user);
        }

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $users = $editForm->getData()->getUsers();
            //If user submitted not contained in original users array, add it
            foreach ($users as $userAdd){
                if (false === $originalUsers->contains($userAdd)) {
                    $team->addUser($userAdd);
                }
            }
            // If user originally contained but not contained in submitted remove the relationship between
            foreach ($originalUsers as $userOriginal) {
                if (false === $team->getUsers()->contains($userOriginal)) {
                    // remove the User from the Team
                    $userOriginal->getTeam()->removeUser($userOriginal);

                    // if it was a many-to-one relationship, remove the relationship like this
                    // $tag->setTask(null);

                    $em->persist($userOriginal);
                    // if you wanted to delete the Tag entirely, you can also do that
                    // $em->remove($tag);
                }
            }
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('team_index');
        }

        return $this->render('AppBundle:team:edit.html.twig', array(
            'team' => $team,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a team entity.
     * @param Request $request
     * @param Team $team
     * @return \Symfony\Component\HttpFoundation\RedirectResponse |Response
     */
    public function deleteAction(Request $request, Team $team)
    {
        $form = $this->createDeleteForm($team);

        if ($request->isXmlHttpRequest()) {
            return $this->render(":common:remove_object_modal.html.twig",
                [
                    "delete_form" => $form->createView(),
                    "object_title" => $team,
                    "default" => false
                ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($team);
            $em->flush();
        }

        return $this->redirectToRoute('team_index');
    }

    /**
     * Creates a form to delete a team entity.
     *
     * @param Team $team The team entity
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(Team $team)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('team_delete', array('slug' => $team->getSlug())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @param Request $request
     * @param Team $user
     * @return StreamedResponse
     */
    public function exportTeamAction(Request $request, Team $user){

        $response = $this->get("object.eximportdatas")->export('admin_export_team', $user)->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllTeamAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("admin_export_team","AppBundle:Team", "Teams" )->prepare($request);

        return $response;
    }


    /**
     * @param Request $request
     * @param Team $team
     * @param $childElement
     * @return JsonResponse
     */
    public function getChildFromParentAction(Request $request, Team $team, $childElement){

        $mappingFunctionName = "get$childElement";
        $childElements = $team->{$mappingFunctionName}();

        $response = $this->get("object.eximportdatas")->serializeInJsonString("user_as_childrow", $childElements);

        return $response;
    }
}
