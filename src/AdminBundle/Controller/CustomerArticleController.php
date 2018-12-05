<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\CustomerArticle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Customerarticle controller.
 *
 */
class CustomerArticleController extends Controller
{
    /**
     * Lists all customerArticle entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $customerArticles = $em->getRepository('AdminBundle:CustomerArticle')->findAll();

        return $this->render('customerarticle/index.html.twig', array(
            'customerArticles' => $customerArticles,
        ));
    }

    /**
     * Creates a new customerArticle entity.
     *
     */
    public function newAction(Request $request)
    {
        $customerArticle = new Customerarticle();
        $form = $this->createForm('AdminBundle\Form\CustomerArticleType', $customerArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($customerArticle);
            $em->flush();

            return $this->redirectToRoute('customerarticle_show', array('id' => $customerArticle->getId()));
        }

        return $this->render('customerarticle/new.html.twig', array(
            'customerArticle' => $customerArticle,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a customerArticle entity.
     *
     */
    public function showAction(CustomerArticle $customerArticle)
    {
        $deleteForm = $this->createDeleteForm($customerArticle);

        return $this->render('customerarticle/show.html.twig', array(
            'customerArticle' => $customerArticle,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing customerArticle entity.
     *
     */
    public function editAction(Request $request, CustomerArticle $customerArticle)
    {
        $deleteForm = $this->createDeleteForm($customerArticle);
        $editForm = $this->createForm('AdminBundle\Form\CustomerArticleType', $customerArticle);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('customerarticle_edit', array('id' => $customerArticle->getId()));
        }

        return $this->render('customerarticle/edit.html.twig', array(
            'customerArticle' => $customerArticle,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a customerArticle entity.
     *
     */
    public function deleteAction(Request $request, CustomerArticle $customerArticle)
    {
        $form = $this->createDeleteForm($customerArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($customerArticle);
            $em->flush();
        }

        return $this->redirectToRoute('customerarticle_index');
    }

    /**
     * Creates a form to delete a customerArticle entity.
     *
     * @param CustomerArticle $customerArticle The customerArticle entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CustomerArticle $customerArticle)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('customerarticle_delete', array('id' => $customerArticle->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
