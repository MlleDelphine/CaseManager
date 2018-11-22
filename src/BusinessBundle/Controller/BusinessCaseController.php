<?php

namespace BusinessBundle\Controller;

use BusinessBundle\Entity\BusinessCase;
use BusinessBundle\Form\BusinessCaseType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * BusinessCase controller.
 *
 */
class BusinessCaseController extends Controller
{
    /**
     * Lists all businessCase entities.
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
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_business_case", $jsonDatas, "BusinessCaseBundle\Entity\BusinessCase");

                $error = $deserialize;
            }else{
                $error = "file_mandatory_error_msg";
            }
        }

        $businessCases = $em->getRepository('BusinessBundle:BusinessCase')->findAll();

        return $this->render('BusinessBundle:businesscase:index.html.twig', array(
            'businessCases' => $businessCases,
            "error" => $error
        ));
    }

    /**
     * Creates a new businessCase entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $businessCase = new BusinessCase();
        $form = $this->createForm(BusinessCaseType::class, $businessCase);
        $form->handleRequest($request);

        if($request->isXmlHttpRequest()){
            return $this->render("BusinessBundle:businesscase:business_case_form.html.twig",['businessCase' => $businessCase,
                'form' => $form->createView()]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($businessCase);
            $em->flush();

            return $this->redirectToRoute('business_case_index');
        }

        return $this->render('BusinessBundle:businesscase:new.html.twig', array(
            'businessCase' => $businessCase,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a businessCase entity.
     * @param BusinessCase $businessCase
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(BusinessCase $businessCase)
    {
        $deleteForm = $this->createDeleteForm($businessCase);

        return $this->render('BusinessBundle:businesscase:show.html.twig', array(
            'businessCase' => $businessCase,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing businessCase entity.
     * @param Request          $request
     * @param BusinessCase $businessCase
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, BusinessCase $businessCase)
    {
        $deleteForm = $this->createDeleteForm($businessCase);
        $editForm = $this->createForm(BusinessCaseType::class, $businessCase);
        $editForm->handleRequest($request);

        if($request->isXmlHttpRequest()){
            return $this->render("BusinessBundle:businesscase:business_case_form.html.twig",['businessCase' => $businessCase,
                'form' => $editForm->createView()]);
        }


        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('business_case_index');
        }

        return $this->render('BusinessBundle:businesscase:edit.html.twig', array(
            'businessCase' => $businessCase,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a businessCase entity.
     * @param Request          $request
     * @param BusinessCase $businessCase
     * @return \Symfony\Component\HttpFoundation\RedirectResponse | Response
     */
    public function deleteAction(Request $request, BusinessCase $businessCase)
    {
        $form = $this->createDeleteForm($businessCase);

        if ($request->isXmlHttpRequest()) {
            return $this->render(":common:remove_object_modal.html.twig",
                [
                    "delete_form" => $form->createView(),
                    "object_title" => $businessCase,
                    "default" => false
                ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($businessCase);
            $em->flush();
        }

        return $this->redirectToRoute('business_case_index');
    }

    /**
     * Creates a form to delete a businessCase entity.
     *
     * @param BusinessCase $businessCase The businessCase entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(BusinessCase $businessCase)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('business_case_delete', array('slug' => $businessCase->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * @param Request $request
     * @param BusinessCase $equipment
     * @return StreamedResponse
     */
    public function exportBusinessCaseAction(Request $request, BusinessCase $equipment){

        $response = $this->get("object.eximportdatas")->export('admin_export_business_case', $equipment)->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllBusinessCaseAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("admin_export_business_case","BusinessCaseBundle:BusinessCase", "Business Cases" )->prepare($request);

        return $response;
    }
}
