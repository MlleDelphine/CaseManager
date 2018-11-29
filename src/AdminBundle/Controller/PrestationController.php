<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Prestation;
use AdminBundle\Form\PrestationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Prestation controller.
 *
 */
class PrestationController extends Controller
{
    /**
     * Lists all prestation entities.
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
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_prestation", $jsonDatas, "AdminBundle\Entity\Prestation");

                $error = $deserialize;
            }else{
                $error = "file_mandatory_error_msg";
            }
        }

        $prestations = $em->getRepository('AdminBundle:Prestation')->findAll();

        return $this->render('AdminBundle:prestation:index.html.twig', array(
            'prestations' => $prestations,
            'error' => $error
        ));
    }

    /**
     * Creates a new prestation entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $prestation = new Prestation();
        $form = $this->createForm(PrestationType::class, $prestation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($prestation);
            $em->flush();

            return $this->redirectToRoute('work_site_type_index');
        }

        return $this->render('AdminBundle:prestation:new.html.twig', array(
            'prestation' => $prestation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a prestation entity.
     * @param Prestation $prestation
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Prestation $prestation)
    {
        $deleteForm = $this->createDeleteForm($prestation);

        return $this->render('AdminBundle:prestation:show.html.twig', array(
            'prestation' => $prestation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing prestation entity.
     * @param Request $request
     * @param Prestation $prestation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Prestation $prestation)
    {
        $deleteForm = $this->createDeleteForm($prestation);
        $editForm = $this->createForm(PrestationType::class, $prestation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('work_site_type_index');
        }

        return $this->render('AdminBundle:prestation:edit.html.twig', array(
            'prestation' => $prestation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a prestation entity.
     * @param Request $request
     * @param Prestation $prestation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse | Response
     */
    public function deleteAction(Request $request, Prestation $prestation)
    {
        $form = $this->createDeleteForm($prestation);

        if ($request->isXmlHttpRequest()) {
            return $this->render(":common:remove_object_modal.html.twig",
                [
                    "delete_form" => $form->createView(),
                    "object_title" => $prestation,
                    "default" => false
                ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($prestation);
            $em->flush();
        }

        return $this->redirectToRoute('work_site_type_index');
    }

    /**
     * Creates a form to delete a prestation entity.
     *
     * @param Prestation $prestation The prestation entity
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(Prestation $prestation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('work_site_type_delete', array('slug' => $prestation->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @param Request $request
     * @param Prestation $prestation
     * @return StreamedResponse
     */
    public function exportPrestationAction(Request $request, Prestation $prestation){

        $response = $this->get("object.eximportdatas")->export('admin_export_prestation', $prestation)->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllPrestationAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("admin_export_prestation","AdminBundle:Prestation", "Prestations" )->prepare($request);

        return $response;
    }
}
