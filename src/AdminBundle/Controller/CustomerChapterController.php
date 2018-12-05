<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\CustomerChapter;
use AdminBundle\Form\CustomerChapterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Customerchapter controller.
 *
 */
class CustomerChapterController extends Controller
{
    /**
     * Lists all customerChapter entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $customerChapters = $em->getRepository('AdminBundle:CustomerChapter')->findAll();

        return $this->render('AdminBundle:customerchapter:index.html.twig', array(
            'customerChapters' => $customerChapters,
        ));
    }

    /**
     * Creates a new customerChapter entity.
     *
     */
    public function newAction(Request $request)
    {
        $customerChapter = new Customerchapter();
        $form = $this->createForm(CustomerChapterType::class, $customerChapter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($customerChapter);
            $em->flush();

            return $this->redirectToRoute('customer_chapter_show', array("slug" => $customerChapter->getSlug()));
        }

        return $this->render('AdminBundle:customerchapter:new.html.twig', array(
            'customerChapter' => $customerChapter,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a customerChapter entity.
     *
     */
    public function showAction(CustomerChapter $customerChapter)
    {
        $deleteForm = $this->createDeleteForm($customerChapter);

        return $this->render('AdminBundle:customerchapter:show.html.twig', array(
            'customerChapter' => $customerChapter,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing customerChapter entity.
     * @param Request $request
     * @param CustomerChapter $customerChapter
     * @return RedirectResponse|Response|JsonResponse
     */
    public function editAction(Request $request, CustomerChapter $customerChapter)
    {
        if ($request->isMethod('PUT')) {
            $em = $this->getDoctrine()->getManager();
            $originalName = $customerChapter->getName();
            $name = $request->get("customer_chapter_name");
            if($name){
                $customerChapter->setName($name);
                $em->persist($customerChapter);
                $em->flush();
                $newEditRoute = $this->generateUrl("customer_chapter_edit", ["slug" => $customerChapter->getSlug()]);
                return new JsonResponse(["slug" => $newEditRoute, "msg" => "Le chapitre \"$originalName\" a bien été renommé en \"$name\"."]);
            }
            return new JsonResponse(["msg" => "Le renommage du chapitre \"$originalName\" a échoué."], 400);
        }

        $deleteForm = $this->createDeleteForm($customerChapter);
        $editForm = $this->createForm(CustomerChapterType::class, $customerChapter);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('customer_chapter_edit', array("slug" => $customerChapter->getSlug()));
        }

        return $this->render('AdminBundle:customerchapter:edit.html.twig', array(
            'customerChapter' => $customerChapter,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a customerChapter entity.
     *
     */
    public function deleteAction(Request $request, CustomerChapter $customerChapter)
    {
        $form = $this->createDeleteForm($customerChapter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($customerChapter);
            $em->flush();
        }

        return $this->redirectToRoute('customer_chapter_index');
    }

    /**
     * Creates a form to delete a customerChapter entity.
     *
     * @param CustomerChapter $customerChapter The customerChapter entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CustomerChapter $customerChapter)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('customer_chapter_delete', array("slug" => $customerChapter->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
