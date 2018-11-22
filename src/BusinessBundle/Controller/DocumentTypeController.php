<?php

namespace BusinessBundle\Controller;

use BusinessBundle\Entity\DocumentType;
use BusinessBundle\Form\DocumentTypeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * DocumentType controller.
 *
 */
class DocumentTypeController extends Controller
{
    /**
     * Lists all documentType entities.
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
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_document_type", $jsonDatas, "DocumentTypeBundle\Entity\DocumentType");

                $error = $deserialize;
            }else{
                $error = "file_mandatory_error_msg";
            }
        }

        $documentTypes = $em->getRepository('BusinessBundle:DocumentType')->findAll();

        return $this->render('BusinessBundle:documenttype:index.html.twig', array(
            'documentTypes' => $documentTypes,
            "error" => $error
        ));
    }

    /**
     * Creates a new documentType entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $documentType = new DocumentType();
        $form = $this->createForm(DocumentTypeType::class, $documentType);
        $form->handleRequest($request);

        if($request->isXmlHttpRequest()){
            return $this->render("BusinessBundle:documenttype:document_type_form.html.twig",['documentType' => $documentType,
                'form' => $form->createView()]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($documentType);
            $em->flush();

            return $this->redirectToRoute('document_type_index');
        }

        return $this->render('BusinessBundle:documenttype:new.html.twig', array(
            'documentType' => $documentType,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a documentType entity.
     * @param DocumentType $documentType
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(DocumentType $documentType)
    {
        $deleteForm = $this->createDeleteForm($documentType);

        return $this->render('BusinessBundle:documenttype:show.html.twig', array(
            'documentType' => $documentType,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing documentType entity.
     * @param Request          $request
     * @param DocumentType $documentType
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, DocumentType $documentType)
    {
        $deleteForm = $this->createDeleteForm($documentType);
        $editForm = $this->createForm(DocumentTypeType::class, $documentType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('document_type_index');
        }

        return $this->render('BusinessBundle:documenttype:edit.html.twig', array(
            'documentType' => $documentType,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a documentType entity.
     * @param Request          $request
     * @param DocumentType $documentType
     * @return \Symfony\Component\HttpFoundation\RedirectResponse | Response
     */
    public function deleteAction(Request $request, DocumentType $documentType)
    {
        $form = $this->createDeleteForm($documentType);

        if ($request->isXmlHttpRequest()) {
            return $this->render(":common:remove_object_modal.html.twig",
                [
                    "delete_form" => $form->createView(),
                    "object_title" => $documentType,
                    "default" => false
                ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($documentType);
            $em->flush();
        }

        return $this->redirectToRoute('document_type_index');
    }

    /**
     * Creates a form to delete a documentType entity.
     *
     * @param DocumentType $documentType The documentType entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(DocumentType $documentType)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('document_type_delete', array('slug' => $documentType->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * @param Request $request
     * @param DocumentType $equipment
     * @return StreamedResponse
     */
    public function exportDocumentTypeAction(Request $request, DocumentType $equipment){

        $response = $this->get("object.eximportdatas")->export('admin_export_document_type', $equipment)->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllDocumentTypeAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("admin_export_document_type","DocumentTypeBundle:DocumentType", "Business Cases" )->prepare($request);

        return $response;
    }
}
