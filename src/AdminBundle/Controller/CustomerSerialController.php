<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\CustomerSerial;
use AdminBundle\Form\CustomerSerialType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Customerserial controller.
 *
 */
class CustomerSerialController extends Controller
{
    /**
     * Lists all customerSerial entities.
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
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_customer_serial", $jsonDatas, "AdminBundle\Entity\Prestation");

                $error = $deserialize;
            }else{
                $error = "file_mandatory_error_msg";
            }
        }

        $customerSerials = $em->getRepository('AdminBundle:CustomerSerial')->findAll();

        return $this->render('AdminBundle:customerserial:index.html.twig', array(
            'customerSerials' => $customerSerials,
            "error" => $error
        ));
    }

    /**
     * Creates a new customerSerial entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $customerSerial = new Customerserial();
        $form = $this->createForm(CustomerSerialType::class, $customerSerial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($customerSerial);
            $em->flush();

            return $this->redirectToRoute('customer_serial_index');
        }

        return $this->render('AdminBundle:customerserial:new.html.twig', array(
            'customerSerial' => $customerSerial,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a customerSerial entity.
     * @param CustomerSerial $customerSerial
     * @return Response
     */
    public function showAction(CustomerSerial $customerSerial)
    {
        $deleteForm = $this->createDeleteForm($customerSerial);

        return $this->render('AdminBundle:customerserial:show.html.twig', array(
            'customerSerial' => $customerSerial,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing customerSerial entity.
     * @param Request $request
     * @param CustomerSerial $customerSerial
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response|JsonResponse
     */
    public function editAction(Request $request, CustomerSerial $customerSerial)
    {
        if ($request->isMethod('PUT')) {
            $em = $this->getDoctrine()->getManager();
            $originalName = $customerSerial->getName();
            $name = $request->get("customer_serial_name");
            if($name){
                $customerSerial->setName($name);
                $em->persist($customerSerial);
                $em->flush();
                $newEditRoute = $this->generateUrl("customer_serial_edit", ["slug" => $customerSerial->getSlug()]);
                return new JsonResponse(["slug" => $newEditRoute, "msg" => "La série \"$originalName\" a bien été renommée en \"$name\""]);
            }
            return new JsonResponse(["msg" => "Le renommage de la série \"$originalName\" a échoué"], 400);
        }

        $deleteForm = $this->createDeleteForm($customerSerial);
        $editForm = $this->createForm(CustomerSerialType::class, $customerSerial);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('customer_serial_edit', array('id' => $customerSerial->getId()));
        }

        return $this->render('AdminBundle:customerserial:edit.html.twig', array(
            'customerSerial' => $customerSerial,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a customerSerial entity.
     * @param Request $request
     * @param CustomerSerial $customerSerial
     * @return \Symfony\Component\HttpFoundation\RedirectResponse | Response
     */
    public function deleteAction(Request $request, CustomerSerial $customerSerial)
    {
        $form = $this->createDeleteForm($customerSerial);
        if ($request->isXmlHttpRequest()) {
            return $this->render(":common:remove_object_modal.html.twig",
                [
                    "delete_form" => $form->createView(),
                    "object_title" => $customerSerial,
                    "default" => false
                ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($customerSerial);
            $em->flush();
        }

        return $this->redirectToRoute('customer_serial_index');
    }

    /**
     * Creates a form to delete a customerSerial entity.
     *
     * @param CustomerSerial $customerSerial The customerSerial entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(CustomerSerial $customerSerial)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('customer_serial_delete', array('slug' => $customerSerial->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }


    /**
     * @param Request $request
     * @param CustomerSerial $customerSerial
     * @return StreamedResponse
     */
    public function exportAction(Request $request, CustomerSerial $customerSerial){

        $response = $this->get("object.eximportdatas")->export('admin_export_customer_serial', $customerSerial)->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("admin_export_customer_serial","AdminBundle:CustomerSerial", "Customer Serials" )->prepare($request);

        return $response;
    }
}
