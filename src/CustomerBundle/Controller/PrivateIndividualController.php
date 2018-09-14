<?php

namespace CustomerBundle\Controller;

use CustomerBundle\Entity\PrivateIndividual;
use CustomerBundle\Form\PrivateIndividualType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Privateindividual controller.
 *
 */
class PrivateIndividualController extends Controller
{
    /**
     * Lists all privateIndividual entities.
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
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_privateindividual", $jsonDatas, "CustomerBundle\Entity\PrivateIndividual");

                $error = $deserialize;
            }else{
                $error = "file_mandatory_error_msg";
            }
        }

        $privateIndividuals = $em->getRepository('CustomerBundle:PrivateIndividual')->findAll();

        return $this->render('CustomerBundle:privateindividual:index.html.twig', array(
            'privateIndividuals' => $privateIndividuals,
            "error" => $error,
            // "delete"
        ));
    }

    /**
     * Creates a new privateIndividual entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $privateIndividual = new Privateindividual();
        $form = $this->createForm(PrivateIndividualType::class, $privateIndividual);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($privateIndividual);
            $em->flush();

            return $this->redirectToRoute("private_individual_index");
        }

        return $this->render('CustomerBundle:privateindividual:new.html.twig', array(
            'privateIndividual' => $privateIndividual,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a privateIndividual entity.
     * @param PrivateIndividual $privateIndividual
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(PrivateIndividual $privateIndividual)
    {
        $deleteForm = $this->createDeleteForm($privateIndividual);

        return $this->render('CustomerBundle:privateindividual:show.html.twig', array(
            'privateIndividual' => $privateIndividual,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing privateIndividual entity.
     * @param Request           $request
     * @param PrivateIndividual $privateIndividual
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, PrivateIndividual $privateIndividual)
    {
        $deleteForm = $this->createDeleteForm($privateIndividual);
        $editForm = $this->createForm(PrivateIndividualType::class, $privateIndividual);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute("private_individual_index");
        }

        return $this->render('CustomerBundle:privateindividual:edit.html.twig', array(
            'privateIndividual' => $privateIndividual,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a privateIndividual entity.
     * @param Request           $request
     * @param PrivateIndividual $privateIndividual
     * @return \Symfony\Component\HttpFoundation\RedirectResponse | Response
     */
    public function deleteAction(Request $request, PrivateIndividual $privateIndividual)
    {
        $form = $this->createDeleteForm($privateIndividual);

        if ($request->isXmlHttpRequest()) {
            return $this->render(":common:remove_object_modal.html.twig",
                [
                    "delete_form" => $form->createView(),
                    "object_title" => $privateIndividual,
                    "default" => false
                ]);
        }
        else{
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($privateIndividual);
                $em->flush();
            }

            return $this->redirectToRoute('private_individual_index');
        }
    }

    /**
     * Creates a form to delete a privateIndividual entity.
     *
     * @param PrivateIndividual $privateIndividual The privateIndividual entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(PrivateIndividual $privateIndividual)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('private_individual_delete', array('slug' => $privateIndividual->getSlug())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @param Request $request
     * @param PrivateIndividual $equipment
     * @return StreamedResponse
     */
    public function exportPrivateIndividualAction(Request $request, PrivateIndividual $equipment){

        $response = $this->get("object.eximportdatas")->export('admin_export_privateindividual', $equipment)->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllPrivateIndividualAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("admin_export_privateindividual","CustomerBundle:PrivateIndividual", "Private Individuals" )->prepare($request);

        return $response;
    }
}
