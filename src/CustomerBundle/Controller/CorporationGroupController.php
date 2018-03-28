<?php

namespace CustomerBundle\Controller;

use CustomerBundle\Entity\CorporationGroup;
use CustomerBundle\Form\CorporationGroupType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * CorporationGroup controller.
 *
 */
class CorporationGroupController extends Controller
{
    /**
     * Lists all corporationGroup entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $corporationGroups = $em->getRepository('CustomerBundle:CorporationGroup')->findAll();

        return $this->render('CustomerBundle:corporationgroup:index.html.twig', array(
            'corporationGroups' => $corporationGroups,
        ));
    }

    /**
     * Creates a new corporationGroup entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $corporationGroup = new Corporationgroup();
        $form = $this->createForm(CorporationGroupType::class, $corporationGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($corporationGroup);
            $em->flush();

            return $this->redirectToRoute('corporation_group_show', array('slug' => $corporationGroup->getSlug()));
        }

        return $this->render('CustomerBundle:corporationgroup:new.html.twig', array(
            'corporationGroup' => $corporationGroup,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a corporationGroup entity.
     * @param CorporationGroup $corporationGroup
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(CorporationGroup $corporationGroup)
    {
        $deleteForm = $this->createDeleteForm($corporationGroup);

        return $this->render('CustomerBundle:corporationgroup:show.html.twig', array(
            'corporationGroup' => $corporationGroup,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing corporationGroup entity.
     * @param Request          $request
     * @param CorporationGroup $corporationGroup
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, CorporationGroup $corporationGroup)
    {
        $deleteForm = $this->createDeleteForm($corporationGroup);
        $editForm = $this->createForm(CorporationGroupType::class, $corporationGroup);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('corporation_group_edit', array('slug' => $corporationGroup->getSlug()));
        }

        return $this->render('CustomerBundle:corporationgroup:edit.html.twig', array(
            'corporationGroup' => $corporationGroup,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a corporationGroup entity.
     * @param Request          $request
     * @param CorporationGroup $corporationGroup
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, CorporationGroup $corporationGroup)
    {
        $form = $this->createDeleteForm($corporationGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($corporationGroup);
            $em->flush();
        }

        return $this->redirectToRoute('corporation_group_index');
    }

    /**
     * Creates a form to delete a corporationGroup entity.
     *
     * @param CorporationGroup $corporationGroup The corporationGroup entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CorporationGroup $corporationGroup)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('corporation_group_delete', array('slug' => $corporationGroup->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}