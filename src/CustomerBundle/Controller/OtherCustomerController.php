<?php

namespace CustomerBundle\Controller;

use CustomerBundle\Entity\OtherCustomer;
use CustomerBundle\Form\OtherCustomerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Township controller.
 *
 */
class OtherCustomerController extends Controller
{
    /**
     * Lists all otherCustomer entities.
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
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_other_customer", $jsonDatas, "CustomerBundle\Entity\OtherCustomer");

                $error = $deserialize;
            }else{
                $error = "file_mandatory_error_msg";
            }
        }

        $otherCustomers = $em->getRepository('CustomerBundle:OtherCustomer')->findAll();

        return $this->render('CustomerBundle:othercustomer:index.html.twig', array(
            'otherCustomers' => $otherCustomers,
            "error" => $error
        ));
    }

    /**
     * Creates a new otherCustomer entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $otherCustomer = new OtherCustomer();
        $form = $this->createForm(OtherCustomerType::class, $otherCustomer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($otherCustomer);
            $em->flush();

            return $this->redirectToRoute('other_customer_index');
        }

        return $this->render('CustomerBundle:othercustomer:new.html.twig', array(
            'otherCustomer' => $otherCustomer,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a otherCustomer entity.
     * @param OtherCustomer $otherCustomer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(OtherCustomer $otherCustomer)
    {
        $deleteForm = $this->createDeleteForm($otherCustomer);

        return $this->render('CustomerBundle:othercustomer:show.html.twig', array(
            'otherCustomer' => $otherCustomer,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing otherCustomer entity.
     * @param Request          $request
     * @param OtherCustomer $otherCustomer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, OtherCustomer $otherCustomer)
    {
        $deleteForm = $this->createDeleteForm($otherCustomer);
        $editForm = $this->createForm(OtherCustomerType::class, $otherCustomer);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('other_customer_index');
        }

        return $this->render('CustomerBundle:othercustomer:edit.html.twig', array(
            'otherCustomer' => $otherCustomer,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a otherCustomer entity.
     * @param Request          $request
     * @param OtherCustomer $otherCustomer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse | Response
     */
    public function deleteAction(Request $request, OtherCustomer $otherCustomer)
    {
        $form = $this->createDeleteForm($otherCustomer);

        if ($request->isXmlHttpRequest()) {
            return $this->render(":common:remove_object_modal.html.twig",
                [
                    "delete_form" => $form->createView(),
                    "object_title" => $otherCustomer,
                    "default" => false
                ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($otherCustomer);
            $em->flush();
        }

        return $this->redirectToRoute('other_customer_index');
    }

    /**
     * Creates a form to delete a otherCustomer entity.
     *
     * @param OtherCustomer $otherCustomer The otherCustomer entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(OtherCustomer $otherCustomer)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('other_customer_delete', array('slug' => $otherCustomer->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * @param Request $request
     * @param OtherCustomer $equipment
     * @return StreamedResponse
     */
    public function exportOtherCustomerAction(Request $request, OtherCustomer $equipment){

        $response = $this->get("object.eximportdatas")->export('admin_export_other_customer', $equipment)->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllOtherCustomerAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("admin_export_other_customer","CustomerBundle:OtherCustomer", "Other Customers" )->prepare($request);

        return $response;
    }
}
