<?php

namespace CustomerBundle\Controller;

use CustomerBundle\Entity\CorporationJobStatus;
use CustomerBundle\Form\CorporationJobStatusType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * CorporationJobStatus controller.
 *
 */
class CorporationJobStatusController extends Controller
{
    /**
     * Lists all corporationJobStatus entities.
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
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_corporationjobstatus", $jsonDatas, "CustomerBundle\Entity\CorporationJobStatus");

                $error = $deserialize;
            }else{
                $error = "file_mandatory_error_msg";
            }
        }

        $corporationJobStatuses = $em->getRepository('CustomerBundle:CorporationJobStatus')->findAll();

        return $this->render('CustomerBundle:corporationjobstatus:index.html.twig', array(
            'corporationJobStatuses' => $corporationJobStatuses,
            'error' => $error
        ));
    }

    /**
     * Creates a new corporationJobStatus entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $corporationJobStatus = new Corporationjobstatus();
        $form = $this->createForm(CorporationJobStatusType::class, $corporationJobStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($corporationJobStatus);
            $em->flush();

            return $this->redirectToRoute('corporation_jobstatus_show', array('slug' => $corporationJobStatus->getSlug()));
        }

        return $this->render('CustomerBundle:corporationjobstatus:new.html.twig', array(
            'corporationJobStatus' => $corporationJobStatus,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a corporationJobStatus entity.
     * @param CorporationJobStatus $corporationJobStatus
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(CorporationJobStatus $corporationJobStatus)
    {
        $deleteForm = $this->createDeleteForm($corporationJobStatus);

        return $this->render('CustomerBundle:corporationjobstatus:show.html.twig', array(
            'corporationJobStatus' => $corporationJobStatus,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing corporationJobStatus entity.
     * @param Request $request
     * @param CorporationJobStatus $corporationJobStatus
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, CorporationJobStatus $corporationJobStatus)
    {
        $deleteForm = $this->createDeleteForm($corporationJobStatus);
        $editForm = $this->createForm(CorporationJobStatusType::class, $corporationJobStatus);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('corporation_jobstatus_edit', array('slug' => $corporationJobStatus->getSlug()));
        }

        return $this->render('CustomerBundle:corporationjobstatus:edit.html.twig', array(
            'corporationJobStatus' => $corporationJobStatus,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a corporationJobStatus entity.
     * @param Request $request
     * @param CorporationJobStatus $corporationJobStatus
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, CorporationJobStatus $corporationJobStatus)
    {
        $form = $this->createDeleteForm($corporationJobStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($corporationJobStatus);
            $em->flush();
        }

        return $this->redirectToRoute('corporation_jobstatus_index');
    }

    /**
     * Creates a form to delete a corporationJobStatus entity.
     *
     * @param CorporationJobStatus $corporationJobStatus The corporationJobStatus entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(CorporationJobStatus $corporationJobStatus)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('corporation_jobstatus_delete', array('slug' => $corporationJobStatus->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @param Request $request
     * @param CorporationJobStatus $corporationJobStatus
     * @return StreamedResponse
     */
    public function exportCorporationJobStatusAction(Request $request, CorporationJobStatus $equipment){

        $response = $this->get("object.eximportdatas")->export('admin_export_corporationjobstatus', $equipment)->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllCorporationJobStatusAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("admin_export_corporationjobstatus","CustomerBundle:CorporationJobStatus", "Corporation Sites" )->prepare($request);

        return $response;
    }
}
