<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Material;
use AppBundle\Form\MaterialType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Material controller.
 *
 */
class MaterialController extends Controller
{
    /**
     * Lists all material entities.
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
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_material", $jsonDatas, "AppBundle\Entity\Material");

                $error = $deserialize;
            }else{
                $error = "file_mandatory_error_msg";
            }
        }

        $materiales = $em->getRepository('AppBundle:Material')->findAll();

        return $this->render('AppBundle:material:index.html.twig', array(
            'materiales' => $materiales,
            'error' => $error
        ));
    }

    /**
     * Creates a new material entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $material = new Material();
        $form = $this->createForm(MaterialType::class, $material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($material);
            $em->flush();

            return $this->redirectToRoute('material_index');
        }

        return $this->render('AppBundle:material:new.html.twig', array(
            'material' => $material,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a material entity.
     * @param Material $material
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Material $material)
    {
        $deleteForm = $this->createDeleteForm($material);

        return $this->render('AppBundle:material:show.html.twig', array(
            'material' => $material,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing material entity.
     * @param Request $request
     * @param Material $material
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Material $material)
    {
        $deleteForm = $this->createDeleteForm($material);
        $editForm = $this->createForm(MaterialType::class, $material, ["MODE_CREATE" => false]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('material_index');
        }

        return $this->render('AppBundle:material:edit.html.twig', array(
            'material' => $material,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a material entity.
     * @param Request $request
     * @param Material $material
     * @return \Symfony\Component\HttpFoundation\RedirectResponse |Response
     */
    public function deleteAction(Request $request, Material $material)
    {
        $form = $this->createDeleteForm($material);

        if ($request->isXmlHttpRequest()) {
            return $this->render(":common:remove_object_modal.html.twig",
                [
                    "delete_form" => $form->createView(),
                    "object_title" => $material,
                    "default" => false
                ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($material);
            $em->flush();
        }

        return $this->redirectToRoute('material_index');
    }

    /**
     * Creates a form to delete a material entity.
     *
     * @param Material $material The material entity
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(Material $material)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('material_delete', array('slug' => $material->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @param Request $request
     * @param Material $material
     * @return StreamedResponse
     */
    public function exportMaterialAction(Request $request, Material $material){

        $response = $this->get("object.eximportdatas")->export('admin_export_material', $material)->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllMaterialAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("admin_export_material","AppBundle:Material", "Materials" )->prepare($request);

        return $response;
    }
}
