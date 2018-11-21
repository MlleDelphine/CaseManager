<?php

namespace CustomerBundle\Controller;

use CustomerBundle\Entity\AbstractClass\Customer;
use CustomerBundle\Entity\CustomerContact;
use CustomerBundle\Entity\CorporationSite;
use CustomerBundle\Form\CustomerContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CustomerContact controller.
 *
 */
class CustomerContactController extends Controller
{
    /**
     * Lists all customerContact entities.
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
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_customercontact", $jsonDatas, "CustomerBundle\Entity\CustomerContact");

                $error = $deserialize;
            }else{
                $error = "file_mandatory_error_msg";
            }
        }

        $customerContacts = $em->getRepository('CustomerBundle:CustomerContact')->findAll();

        return $this->render('CustomerBundle:customercontact:index.html.twig', array(
            'customerContacts' => $customerContacts,
            "error" => $error
        ));
    }

    /**
     * Creates a new customerContact entity.
     * @param Request $request
     * @param Customer $customer
     *
     * @ParamConverter("customer", class="CustomerBundle:AbstractClass\Customer", options={"mapping": {"slugCustomer" : "slug"}}, isOptional="true" )
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, Customer $customer = null)
    {
        $customerContact = new CustomerContact();
        if(isset($customer)){
            $customerContact->setCustomer($customer);
        }
        $form = $this->createForm(CustomerContactType::class, $customerContact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($customerContact);
            $em->flush();

            return $this->redirectToRoute('customer_contact_index');
        }

        return $this->render('CustomerBundle:customercontact:new.html.twig', array(
            'customerContact' => $customerContact,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a customerContact entity.
     * @param CustomerContact $customerContact
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(CustomerContact $customerContact)
    {
        $deleteForm = $this->createDeleteForm($customerContact);

        return $this->render('CustomerBundle:customercontact:show.html.twig', array(
            'customerContact' => $customerContact,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing customerContact entity.
     * @param Request          $request
     * @param CustomerContact $customerContact
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, CustomerContact $customerContact)
    {
        $deleteForm = $this->createDeleteForm($customerContact);
        $editForm = $this->createForm(CustomerContactType::class, $customerContact);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('customer_contact_index');
        }

        return $this->render('CustomerBundle:customercontact:edit.html.twig', array(
            'customerContact' => $customerContact,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a customerContact entity.
     * @param Request          $request
     * @param CustomerContact $customerContact
     * @return \Symfony\Component\HttpFoundation\RedirectResponse | Response
     */
    public function deleteAction(Request $request, CustomerContact $customerContact)
    {
        $form = $this->createDeleteForm($customerContact);

        if ($request->isXmlHttpRequest()) {
            return $this->render(":common:remove_object_modal.html.twig",
                [
                    "delete_form" => $form->createView(),
                    "object_title" => $customerContact,
                    "default" => false
                ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($customerContact);
            $em->flush();
        }

        return $this->redirectToRoute('customer_contact_index');
    }

    /**
     * Creates a form to delete a customerContact entity.
     *
     * @param CustomerContact $customerContact The customerContact entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(CustomerContact $customerContact)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('customer_contact_delete', array('slug' => $customerContact->getSlug())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @param Request $request
     * @param CustomerContact $equipment
     * @return StreamedResponse
     */
    public function exportCustomerContactAction(Request $request, CustomerContact $equipment){

        $response = $this->get("object.eximportdatas")->export('admin_export_customercontact', $equipment)->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllCustomerContactAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("admin_export_customercontact","CustomerBundle:CustomerContact", "Corporation Sites" )->prepare($request);

        return $response;
    }
}
