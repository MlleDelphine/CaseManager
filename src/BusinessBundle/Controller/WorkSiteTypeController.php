<?php

namespace BusinessBundle\Controller;

use BusinessBundle\Entity\WorkSite;
use BusinessBundle\Form\WorkSiteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * WorkSite controller.
 *
 */
class WorkSiteController extends Controller
{
    /**
     * Lists all workSite entities.
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
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_work_site_type", $jsonDatas, "WorkSiteBundle\Entity\WorkSite");

                $error = $deserialize;
            }else{
                $error = "file_mandatory_error_msg";
            }
        }

        $workSites = $em->getRepository('BusinessBundle:WorkSite')->findAll();

        return $this->render('BusinessBundle:worksite:index.html.twig', array(
            'workSites' => $workSites,
            "error" => $error
        ));
    }

    /**
     * Creates a new workSite entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $workSite = new WorkSite();
        $form = $this->createForm(WorkSiteType::class, $workSite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($workSite);
            $em->flush();

            return $this->redirectToRoute('business_work_site_type_index');
        }

        return $this->render('BusinessBundle:worksite:new.html.twig', array(
            'workSite' => $workSite,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a workSite entity.
     * @param WorkSite $workSite
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(WorkSite $workSite)
    {
        $deleteForm = $this->createDeleteForm($workSite);

        return $this->render('BusinessBundle:worksite:show.html.twig', array(
            'workSite' => $workSite,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing workSite entity.
     * @param Request          $request
     * @param WorkSite $workSite
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, WorkSite $workSite)
    {
        $deleteForm = $this->createDeleteForm($workSite);
        $editForm = $this->createForm(WorkSiteType::class, $workSite);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('business_work_site_type_index');
        }

        return $this->render('BusinessBundle:worksite:edit.html.twig', array(
            'workSite' => $workSite,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a workSite entity.
     * @param Request          $request
     * @param WorkSite $workSite
     * @return \Symfony\Component\HttpFoundation\RedirectResponse | Response
     */
    public function deleteAction(Request $request, WorkSite $workSite)
    {
        $form = $this->createDeleteForm($workSite);

        if ($request->isXmlHttpRequest()) {
            return $this->render(":common:remove_object_modal.html.twig",
                [
                    "delete_form" => $form->createView(),
                    "object_title" => $workSite,
                    "default" => false
                ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($workSite);
            $em->flush();
        }

        return $this->redirectToRoute('business_work_site_type_index');
    }

    /**
     * Creates a form to delete a workSite entity.
     *
     * @param WorkSite $workSite The workSite entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(WorkSite $workSite)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('business_work_site_type_delete', array('slug' => $workSite->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * @param Request $request
     * @param WorkSite $equipment
     * @return StreamedResponse
     */
    public function exportWorkSiteAction(Request $request, WorkSite $equipment){

        $response = $this->get("object.eximportdatas")->export('admin_export_work_site_type', $equipment)->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllWorkSiteAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("admin_export_work_site_type","WorkSiteBundle:WorkSite", "Business WorkSite Types" )->prepare($request);

        return $response;
    }
}
