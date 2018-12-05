<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\CustomerChapter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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

        return $this->render('customerchapter/index.html.twig', array(
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
        $form = $this->createForm('AdminBundle\Form\CustomerChapterType', $customerChapter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($customerChapter);
            $em->flush();

            return $this->redirectToRoute('customerchapter_show', array('id' => $customerChapter->getId()));
        }

        return $this->render('customerchapter/new.html.twig', array(
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

        return $this->render('customerchapter/show.html.twig', array(
            'customerChapter' => $customerChapter,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing customerChapter entity.
     * @param Request $request
     * @param CustomerChapter $customerChapter
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, CustomerChapter $customerChapter)
    {
        $deleteForm = $this->createDeleteForm($customerChapter);
        $editForm = $this->createForm('AdminBundle\Form\CustomerChapterType', $customerChapter);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('customerchapter_edit', array('id' => $customerChapter->getId()));
        }

        return $this->render('customerchapter/edit.html.twig', array(
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

        return $this->redirectToRoute('customerchapter_index');
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
            ->setAction($this->generateUrl('customerchapter_delete', array('id' => $customerChapter->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
