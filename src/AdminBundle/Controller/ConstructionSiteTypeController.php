<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\ConstructionSiteType;
use AdminBundle\Form\ConstructionSiteTypeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * ConstructionSiteType controller.
 *
 */
class ConstructionSiteTypeController extends Controller
{
    /**
     * Lists all constructionSiteType entities.
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
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_constructionsitetype", $jsonDatas, "AdminBundle\Entity\ConstructionSiteType");

                $error = $deserialize;
            }else{
                $error = "file_mandatory_error_msg";
            }
        }

        $constructionSiteTypes = $em->getRepository('AdminBundle:ConstructionSiteType')->findAll();

        return $this->render('AdminBundle:constructionsitetype:index.html.twig', array(
            'constructionSiteTypes' => $constructionSiteTypes,
            'error' => $error
        ));
    }

    /**
     * Creates a new constructionSiteType entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $constructionSiteType = new ConstructionSiteType();
        $form = $this->createForm(ConstructionSiteTypeType::class, $constructionSiteType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($constructionSiteType);
            $em->flush();

            return $this->redirectToRoute('construction_site_type_index');
        }

        return $this->render('AdminBundle:constructionsitetype:new.html.twig', array(
            'constructionSiteType' => $constructionSiteType,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a constructionSiteType entity.
     * @param ConstructionSiteType $constructionSiteType
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(ConstructionSiteType $constructionSiteType)
    {
        $deleteForm = $this->createDeleteForm($constructionSiteType);

        return $this->render('AdminBundle:constructionsitetype:show.html.twig', array(
            'constructionSiteType' => $constructionSiteType,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing constructionSiteType entity.
     * @param Request $request
     * @param ConstructionSiteType $constructionSiteType
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, ConstructionSiteType $constructionSiteType)
    {
        $deleteForm = $this->createDeleteForm($constructionSiteType);
        $editForm = $this->createForm(ConstructionSiteTypeType::class, $constructionSiteType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('construction_site_type_index');
        }

        return $this->render('AdminBundle:constructionsitetype:edit.html.twig', array(
            'constructionSiteType' => $constructionSiteType,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a constructionSiteType entity.
     * @param Request $request
     * @param ConstructionSiteType $constructionSiteType
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, ConstructionSiteType $constructionSiteType)
    {
        $form = $this->createDeleteForm($constructionSiteType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($constructionSiteType);
            $em->flush();
        }

        return $this->redirectToRoute('construction_site_type_index');
    }

    /**
     * Creates a form to delete a constructionSiteType entity.
     *
     * @param ConstructionSiteType $constructionSiteType The constructionSiteType entity
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(ConstructionSiteType $constructionSiteType)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('construction_site_type_delete', array('slug' => $constructionSiteType->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @param Request $request
     * @param ConstructionSiteType $constructionSiteType
     * @return StreamedResponse
     */
    public function exportConstructionSiteTypeAction(Request $request, ConstructionSiteType $constructionSiteType){

        $response = $this->get("object.eximportdatas")->export('admin_export_constructionsitetype', $constructionSiteType)->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllConstructionSiteTypeAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("admin_export_constructionsitetype","AdminBundle:ConstructionSiteType", "ConstructionSiteTypes" )->prepare($request);

        return $response;
    }
}
