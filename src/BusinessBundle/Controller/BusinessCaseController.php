<?php

namespace BusinessBundle\Controller;

use BusinessBundle\Entity\BusinessCase;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Businesscase controller.
 *
 */
class BusinessCaseController extends Controller
{
    /**
     * Lists all businessCase entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $businessCases = $em->getRepository('BusinessBundle:BusinessCase')->findAll();

        return $this->render('businesscase/index.html.twig', array(
            'businessCases' => $businessCases,
        ));
    }

    /**
     * Creates a new businessCase entity.
     *
     */
    public function newAction(Request $request)
    {
        $businessCase = new Businesscase();
        $form = $this->createForm('BusinessBundle\Form\BusinessCaseType', $businessCase);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($businessCase);
            $em->flush();

            return $this->redirectToRoute('business-case_show', array('id' => $businessCase->getId()));
        }

        return $this->render('businesscase/new.html.twig', array(
            'businessCase' => $businessCase,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a businessCase entity.
     *
     */
    public function showAction(BusinessCase $businessCase)
    {
        $deleteForm = $this->createDeleteForm($businessCase);

        return $this->render('businesscase/show.html.twig', array(
            'businessCase' => $businessCase,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing businessCase entity.
     *
     */
    public function editAction(Request $request, BusinessCase $businessCase)
    {
        $deleteForm = $this->createDeleteForm($businessCase);
        $editForm = $this->createForm('BusinessBundle\Form\BusinessCaseType', $businessCase);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('business-case_edit', array('id' => $businessCase->getId()));
        }

        return $this->render('businesscase/edit.html.twig', array(
            'businessCase' => $businessCase,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a businessCase entity.
     *
     */
    public function deleteAction(Request $request, BusinessCase $businessCase)
    {
        $form = $this->createDeleteForm($businessCase);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($businessCase);
            $em->flush();
        }

        return $this->redirectToRoute('business-case_index');
    }

    /**
     * Creates a form to delete a businessCase entity.
     *
     * @param BusinessCase $businessCase The businessCase entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(BusinessCase $businessCase)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('business-case_delete', array('id' => $businessCase->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
