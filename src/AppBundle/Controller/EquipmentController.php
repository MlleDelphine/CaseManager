<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Equipment;
use AppBundle\Form\EquipmentType;
use SecurityAppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Equipment controller.
 *
 */
class EquipmentController extends Controller
{
    /**
     * Lists all equipment entities.
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
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_equipment", $jsonDatas, "AppBundle\Entity\Equipment");

                $error = $deserialize;
            }else{
                $error = "file_mandatory_error_msg";
            }
        }

        $equipments = $em->getRepository('AppBundle:Equipment')->findAll();

        return $this->render('AppBundle:equipment:index.html.twig', array(
            'equipments' => $equipments,
            'error' => $error
        ));
    }

    /**
     * Creates a new equipment entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $equipment = new Equipment();
        $form = $this->createForm(EquipmentType::class, $equipment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($equipment);
            $em->flush();

            return $this->redirectToRoute('equipment_index');
        }

        return $this->render('AppBundle:equipment:new.html.twig', array(
            'equipment' => $equipment,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a equipment entity.
     * @param Equipment $equipment
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Equipment $equipment)
    {
        $deleteForm = $this->createDeleteForm($equipment);

        return $this->render('AppBundle:equipment:show.html.twig', array(
            'equipment' => $equipment,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing equipment entity.
     * @param Request $request
     * @param Equipment $equipment
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Equipment $equipment)
    {
        $deleteForm = $this->createDeleteForm($equipment);
        $editForm = $this->createForm(EquipmentType::class, $equipment, ["MODE_CREATE" => false]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('equipment_index');
        }

        return $this->render('AppBundle:equipment:edit.html.twig', array(
            'equipment' => $equipment,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a equipment entity.
     * @param Request $request
     * @param Equipment $equipment
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Equipment $equipment)
    {
        $form = $this->createDeleteForm($equipment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($equipment);
            $em->flush();
        }

        return $this->redirectToRoute('equipment_index');
    }

    /**
     * Creates a form to delete a equipment entity.
     *
     * @param Equipment $equipment The equipment entity
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(Equipment $equipment)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('equipment_delete', array('slug' => $equipment->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @param Request $request
     * @param Equipment $equipment
     * @return StreamedResponse
     */
    public function exportEquipmentAction(Request $request, Equipment $equipment){

        $response = $this->get("object.eximportdatas")->export('admin_export_equipment', $equipment)->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllEquipmentAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("admin_export_equipment","AppBundle:Equipment", "Equipments" )->prepare($request);

        return $response;
    }
}
