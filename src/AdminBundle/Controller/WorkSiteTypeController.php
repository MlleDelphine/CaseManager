<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\WorkSiteType;
use AdminBundle\Form\WorkSiteTypeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * WorkSiteType controller.
 * "Domaine de prestation"
 *
 */
class WorkSiteTypeController extends Controller
{
    /**
     * Lists all workSiteType entities.
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
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_worksitetype", $jsonDatas, "AdminBundle\Entity\WorkSiteType");

                $error = $deserialize;
            }else{
                $error = "file_mandatory_error_msg";
            }
        }

        $workSiteTypes = $em->getRepository('AdminBundle:WorkSiteType')->findAll();

        return $this->render('AdminBundle:worksitetype:index.html.twig', array(
            'workSiteTypes' => $workSiteTypes,
            'error' => $error
        ));
    }

    /**
     * Creates a new workSiteType entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $workSiteType = new WorkSiteType();
        $form = $this->createForm(WorkSiteTypeType::class, $workSiteType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($workSiteType);
            $em->flush();

            return $this->redirectToRoute('work_site_type_index');
        }

        return $this->render('AdminBundle:worksitetype:new.html.twig', array(
            'workSiteType' => $workSiteType,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a workSiteType entity.
     * @param WorkSiteType $workSiteType
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(WorkSiteType $workSiteType)
    {
        $deleteForm = $this->createDeleteForm($workSiteType);

        return $this->render('AdminBundle:worksitetype:show.html.twig', array(
            'workSiteType' => $workSiteType,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing workSiteType entity.
     * @param Request $request
     * @param WorkSiteType $workSiteType
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, WorkSiteType $workSiteType)
    {
        $deleteForm = $this->createDeleteForm($workSiteType);
        $editForm = $this->createForm(WorkSiteTypeType::class, $workSiteType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('work_site_type_index');
        }

        return $this->render('AdminBundle:worksitetype:edit.html.twig', array(
            'workSiteType' => $workSiteType,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a workSiteType entity.
     * @param Request $request
     * @param WorkSiteType $workSiteType
     * @return \Symfony\Component\HttpFoundation\RedirectResponse | Response
     */
    public function deleteAction(Request $request, WorkSiteType $workSiteType)
    {
        $form = $this->createDeleteForm($workSiteType);

        if ($request->isXmlHttpRequest()) {
            return $this->render(":common:remove_object_modal.html.twig",
                [
                    "delete_form" => $form->createView(),
                    "object_title" => $workSiteType,
                    "default" => false
                ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($workSiteType);
            $em->flush();
        }

        return $this->redirectToRoute('work_site_type_index');
    }

    /**
     * Creates a form to delete a workSiteType entity.
     *
     * @param WorkSiteType $workSiteType The workSiteType entity
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(WorkSiteType $workSiteType)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('work_site_type_delete', array('slug' => $workSiteType->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @param Request $request
     * @param WorkSiteType $workSiteType
     * @return StreamedResponse
     */
    public function exportWorkSiteTypeAction(Request $request, WorkSiteType $workSiteType){

        $response = $this->get("object.eximportdatas")->export('admin_export_worksitetype', $workSiteType)->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllWorkSiteTypeAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("admin_export_worksitetype","AdminBundle:WorkSiteType", "WorkSiteTypes" )->prepare($request);

        return $response;
    }
}
