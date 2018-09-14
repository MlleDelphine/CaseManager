<?php

namespace CustomerBundle\Controller;

use CustomerBundle\Entity\CorporationEmployee;
use CustomerBundle\Entity\CorporationSite;
use CustomerBundle\Form\CorporationEmployeeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CorporationEmployee controller.
 *
 */
class CorporationEmployeeController extends Controller
{
    /**
     * Lists all corporationEmployee entities.
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
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_corporationemployee", $jsonDatas, "CustomerBundle\Entity\CorporationEmployee");

                $error = $deserialize;
            }else{
                $error = "file_mandatory_error_msg";
            }
        }

        $corporationEmployees = $em->getRepository('CustomerBundle:CorporationEmployee')->findAll();

        return $this->render('CustomerBundle:corporationemployee:index.html.twig', array(
            'corporationEmployees' => $corporationEmployees,
            "error" => $error
        ));
    }

    /**
     * Creates a new corporationEmployee entity.
     * @param Request $request
     * @param CorporationSite $corporationSite
     *
     * @ParamConverter("corporationSite", class="CustomerBundle:CorporationSite", options={"mapping": {"slugCorpSite" : "slug"}}, isOptional="true" )
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, CorporationSite $corporationSite = null)
    {
        $corporationEmployee = new CorporationEmployee();
        if(isset($corporationSite)){
            $corporationEmployee->setCorporationSite($corporationSite);
        }
        $form = $this->createForm(CorporationEmployeeType::class, $corporationEmployee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($corporationEmployee);
            $em->flush();

            return $this->redirectToRoute('corporation_employee_index');
        }

        return $this->render('CustomerBundle:corporationemployee:new.html.twig', array(
            'corporationEmployee' => $corporationEmployee,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a corporationEmployee entity.
     * @param CorporationEmployee $corporationEmployee
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(CorporationEmployee $corporationEmployee)
    {
        $deleteForm = $this->createDeleteForm($corporationEmployee);

        return $this->render('CustomerBundle:corporationemployee:show.html.twig', array(
            'corporationEmployee' => $corporationEmployee,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing corporationEmployee entity.
     * @param Request          $request
     * @param CorporationEmployee $corporationEmployee
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, CorporationEmployee $corporationEmployee)
    {
        $deleteForm = $this->createDeleteForm($corporationEmployee);
        $editForm = $this->createForm(CorporationEmployeeType::class, $corporationEmployee);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('corporation_employee_index');
        }

        return $this->render('CustomerBundle:corporationemployee:edit.html.twig', array(
            'corporationEmployee' => $corporationEmployee,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a corporationEmployee entity.
     * @param Request          $request
     * @param CorporationEmployee $corporationEmployee
     * @return \Symfony\Component\HttpFoundation\RedirectResponse | Response
     */
    public function deleteAction(Request $request, CorporationEmployee $corporationEmployee)
    {
        $form = $this->createDeleteForm($corporationEmployee);

        if ($request->isXmlHttpRequest()) {
            return $this->render(":common:remove_object_modal.html.twig",
                [
                    "delete_form" => $form->createView(),
                    "object_title" => $corporationEmployee,
                    "default" => false
                ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($corporationEmployee);
            $em->flush();
        }

        return $this->redirectToRoute('corporation_employee_index');
    }

    /**
     * Creates a form to delete a corporationEmployee entity.
     *
     * @param CorporationEmployee $corporationEmployee The corporationEmployee entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(CorporationEmployee $corporationEmployee)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('corporation_employee_delete', array('slug' => $corporationEmployee->getSlug())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @param Request $request
     * @param CorporationEmployee $equipment
     * @return StreamedResponse
     */
    public function exportCorporationEmployeeAction(Request $request, CorporationEmployee $equipment){

        $response = $this->get("object.eximportdatas")->export('admin_export_corporationemployee', $equipment)->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllCorporationEmployeeAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("admin_export_corporationemployee","CustomerBundle:CorporationEmployee", "Corporation Sites" )->prepare($request);

        return $response;
    }
}
