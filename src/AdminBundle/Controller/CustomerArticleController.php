<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\CustomerArticle;
use AdminBundle\Entity\CustomerChapter;
use AdminBundle\Form\CustomerArticleType;
use AdminBundle\Form\CustomerChapterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Customerarticle controller.
 *
 */
class CustomerArticleController extends Controller
{
    /**
     * Lists all customerArticle entities.
     * @param Request $request
     * @return Response
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
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_customerarticle", $jsonDatas, "AdminBundle\Entity\CustomerArticle");

                $error = $deserialize;
            }else{
                $error = "file_mandatory_error_msg";
            }
        }

        $customerArticles = $em->getRepository('AdminBundle:CustomerArticle')->findAll();

        return $this->render('AdminBundle:CustomerArticle:index.html.twig', array(
            'customerArticles' => $customerArticles,
            "error" => $error
        ));
    }

    /**
     * Creates a new customerArticle entity.
     * @param Request $request
     * @param CustomerChapter $customerChapter
     *
     * @ParamConverter("customerChapter", class="AdminBundle:CustomerChapter", options={"mapping": {"slugChapter" : "slug"}}, isOptional="true" )
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request, CustomerChapter $customerChapter)
    {
        $customerArticle = new Customerarticle();
        if(isset($customerChapter)){
            $customerArticle->setCustomerChapter($customerChapter);
        }
        $form = $this->createForm(CustomerArticleType::class, $customerArticle, ["mode" => CustomerArticleType::POP_UP_MODE, "action" => $this->generateUrl("customer_article_new")]);

        /**
         * INSIDE POP UP
         */
        if ($request->isXmlHttpRequest()) {
            return $this->render("AdminBundle:customerarticle:add_article_modal.html.twig",
                [
                    "form" => $form->createView(),
                    "object_title" => $customerArticle,
                    "default" => false
                ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($customerArticle);
            $em->flush();

            return $this->redirectToRoute('customer_article_show', array('id' => $customerArticle->getId()));
        }

        return $this->render('AdminBundle:CustomerArticle:new.html.twig', array(
            'customerArticle' => $customerArticle,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a customerArticle entity.
     * @param CustomerArticle $customerArticle
     * @return Response
     */
    public function showAction(CustomerArticle $customerArticle)
    {
        $deleteForm = $this->createDeleteForm($customerArticle);

        return $this->render('AdminBundle:CustomerArticle:show.html.twig', array(
            'customerArticle' => $customerArticle,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing customerArticle entity.
     * @param Request $request
     * @param CustomerArticle $customerArticle
     * @return RedirectResponse|Response
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

        return $this->render('AdminBundle:CustomerArticle:edit.html.twig', array(
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
