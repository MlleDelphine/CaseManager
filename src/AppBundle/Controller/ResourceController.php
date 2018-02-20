<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Resource;
use AppBundle\Form\ResourceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Resource controller.
 *
 */
class ResourceController extends Controller
{
    /**
     * Lists all resource entities.
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
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_resource", $jsonDatas, "AppBundle\Entity\Resource");

                $error = $deserialize;
            }else{
                $error = "You must provide a file!";
            }
        }

        $resources = $em->getRepository('AppBundle:Resource')->findAll();

        return $this->render('AppBundle:resource:index.html.twig', array(
            'resources' => $resources,
            'error' => $error
        ));
    }

    /**
     * Creates a new resource entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $resource = new Resource();
        $form = $this->createForm(ResourceType::class, $resource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($resource);
            $em->flush();

            return $this->redirectToRoute('resource_index');
        }

        return $this->render('AppBundle:resource:new.html.twig', array(
            'resource' => $resource,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a resource entity.
     * @param Resource $resource
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Resource $resource)
    {
        $deleteForm = $this->createDeleteForm($resource);

        return $this->render('AppBundle:resource:show.html.twig', array(
            'resource' => $resource,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing resource entity.
     * @param Request $request
     * @param Resource $resource
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Resource $resource)
    {
        $deleteForm = $this->createDeleteForm($resource);
        $editForm = $this->createForm(ResourceType::class, $resource, ["MODE_CREATE" => false]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('resource_index');
        }

        return $this->render('AppBundle:resource:edit.html.twig', array(
            'resource' => $resource,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a resource entity.
     * @param Request $request
     * @param Resource $resource
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Resource $resource)
    {
        $form = $this->createDeleteForm($resource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($resource);
            $em->flush();
        }

        return $this->redirectToRoute('resource_index');
    }

    /**
     * Creates a form to delete a resource entity.
     *
     * @param Resource $resource The resource entity
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(Resource $resource)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('resource_delete', array('slug' => $resource->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @param Request $request
     * @param Resource $resource
     * @return StreamedResponse
     */
    public function exportResourceAction(Request $request, Resource $resource){

        $response = $this->get("object.eximportdatas")->export('admin_export_resource', $resource)->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllResourceAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("admin_export_resource","AppBundle:Resource", "Resources" )->prepare($request);

        return $response;
    }
}
