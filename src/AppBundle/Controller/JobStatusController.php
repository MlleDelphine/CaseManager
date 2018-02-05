<?php

namespace AppBundle\Controller;

use AppBundle\Entity\JobStatus;
use AppBundle\Form\JobStatusType;
use SecurityAppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
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
                $deserialize = $this->get('object.eximportdatas')->import("bo_export_jobstatus", $jsonDatas, "AppBundle\Entity\User");

                $error = $deserialize;
            }else{
                $error = "You must provide a file!";
            }
        }

        $jobStatuses = $em->getRepository('AppBundle:JobStatus')->findAll();

        return $this->render('AppBundle:jobstatus:index.html.twig', array(
            'jobStatuses' => $jobStatuses,
            'error' => $error
        ));
    }

    /**
     * Creates a new jobStatus entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $jobStatus = new Jobstatus();
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, JobStatus $jobStatus)
    {
        $form = $this->createDeleteForm($jobStatus);
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
     * @param User $user
     * @return StreamedResponse
     */
    public function exportUserAction(Request $request, User $user){

        $response = $this->get("object.eximportdatas")->export('bo_export_jobstatus', $user)->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllUserAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("bo_export_jobstatus","AppBundle:User", "Users" )->prepare($request);

        return $response;
    }
}
