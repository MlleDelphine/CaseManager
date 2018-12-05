<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\UnitTimePoint;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Unittimepoint controller.
 *
 */
class UnitTimePointController extends Controller
{
    /**
     * Lists all unitTimePoint entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $unitTimePoints = $em->getRepository('AdminBundle:UnitTimePoint')->findAll();

        return $this->render('unittimepoint/index.html.twig', array(
            'unitTimePoints' => $unitTimePoints,
        ));
    }

    /**
     * Creates a new unitTimePoint entity.
     *
     */
    public function newAction(Request $request)
    {
        $unitTimePoint = new Unittimepoint();
        $form = $this->createForm('AdminBundle\Form\UnitTimePointType', $unitTimePoint);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($unitTimePoint);
            $em->flush();

            return $this->redirectToRoute('unittimepoint_show', array('id' => $unitTimePoint->getId()));
        }

        return $this->render('unittimepoint/new.html.twig', array(
            'unitTimePoint' => $unitTimePoint,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a unitTimePoint entity.
     *
     */
    public function showAction(UnitTimePoint $unitTimePoint)
    {
        $deleteForm = $this->createDeleteForm($unitTimePoint);

        return $this->render('unittimepoint/show.html.twig', array(
            'unitTimePoint' => $unitTimePoint,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing unitTimePoint entity.
     *
     */
    public function editAction(Request $request, UnitTimePoint $unitTimePoint)
    {
        $deleteForm = $this->createDeleteForm($unitTimePoint);
        $editForm = $this->createForm('AdminBundle\Form\UnitTimePointType', $unitTimePoint);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('unittimepoint_edit', array('id' => $unitTimePoint->getId()));
        }

        return $this->render('unittimepoint/edit.html.twig', array(
            'unitTimePoint' => $unitTimePoint,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a unitTimePoint entity.
     *
     */
    public function deleteAction(Request $request, UnitTimePoint $unitTimePoint)
    {
        $form = $this->createDeleteForm($unitTimePoint);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($unitTimePoint);
            $em->flush();
        }

        return $this->redirectToRoute('unittimepoint_index');
    }

    /**
     * Creates a form to delete a unitTimePoint entity.
     *
     * @param UnitTimePoint $unitTimePoint The unitTimePoint entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(UnitTimePoint $unitTimePoint)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('unittimepoint_delete', array('id' => $unitTimePoint->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
