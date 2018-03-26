<?php

namespace CustomerBundle\Controller;

use CustomerBundle\Entity\CorporationSite;
use CustomerBundle\Form\CorporationSiteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * CorporationSite controller.
 *
 */
class CorporationSiteController extends Controller
{
    /**
     * Lists all corporationSite entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $corporationSites = $em->getRepository('CustomerBundle:CorporationSite')->findAll();

        return $this->render('CustomerBundle:corporationsite:index.html.twig', array(
            'corporationSites' => $corporationSites,
        ));
    }

    /**
     * Creates a new corporationSite entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $corporationSite = new Corporationsite();
        $form = $this->createForm(CorporationSiteType::class, $corporationSite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($corporationSite);
            $em->flush();

            return $this->redirectToRoute('corporation_site_show', array('slug' => $corporationSite->getSlug()));
        }

        return $this->render('CustomerBundle:corporationsite:new.html.twig', array(
            'corporationSite' => $corporationSite,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a corporationSite entity.
     * @param CorporationSite $corporationSite
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(CorporationSite $corporationSite)
    {
        $deleteForm = $this->createDeleteForm($corporationSite);

        return $this->render('CustomerBundle:corporationsite:show.html.twig', array(
            'corporationSite' => $corporationSite,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing corporationSite entity.
     * @param Request         $request
     * @param CorporationSite $corporationSite
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, CorporationSite $corporationSite)
    {
        $deleteForm = $this->createDeleteForm($corporationSite);
        $editForm = $this->createForm(CorporationSiteType::class, $corporationSite);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('corporation_site_edit', array('slug' => $corporationSite->getSlug()));
        }

        return $this->render('CustomerBundle:corporationsite:edit.html.twig', array(
            'corporationSite' => $corporationSite,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a corporationSite entity.
     * @param Request         $request
     * @param CorporationSite $corporationSite
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, CorporationSite $corporationSite)
    {
        $form = $this->createDeleteForm($corporationSite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($corporationSite);
            $em->flush();
        }

        return $this->redirectToRoute('corporation_site_index');
    }

    /**
     * Creates a form to delete a corporationSite entity.
     *
     * @param CorporationSite $corporationSite The corporationSite entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CorporationSite $corporationSite)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('corporation_site_delete', array('slug' => $corporationSite->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
