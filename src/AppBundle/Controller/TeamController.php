<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Team;
use AppBundle\Form\TeamType;
use Doctrine\Common\Collections\ArrayCollection;
use SecurityAppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
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
                $deserialize = $this->get('object.eximportdatas')->import("bo_export_team", $jsonDatas, "AppBundle\Entity\User");

                $error = $deserialize;
            }else{
                $error = "You must provide a file!";
            }
        }

        $teams = $em->getRepository('AppBundle:Team')->findAll();

        return $this->render('AppBundle:team:index.html.twig', array(
            'teams' => $teams,
            'error' => $error
        ));
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
            throw $this->createNotFoundException('No team found for id '.$id);
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
                    // remove the Task from the Tag
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Team $team)
    {
        $form = $this->createDeleteForm($team);
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
            ->getForm()
            ;
    }

    /**
     * @param Request $request
     * @param User $user
     * @return StreamedResponse
     */
    public function exportUserAction(Request $request, User $user){

        $response = $this->get("object.eximportdatas")->export('bo_export_team', $user)->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllUserAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("bo_export_team","AppBundle:User", "Users" )->prepare($request);

        return $response;
    }
}
