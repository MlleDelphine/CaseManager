<?php

namespace CustomerBundle\Controller;

use CustomerBundle\Entity\TownShip;
use CustomerBundle\Form\TownShipType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Township controller.
 *
 */
class TownShipController extends Controller
{
    /**
     * Lists all townShip entities.
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
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_township", $jsonDatas, "CustomerBundle\Entity\TownShip");

                $error = $deserialize;
            }else{
                $error = "file_mandatory_error_msg";
            }
        }

        $townShips = $em->getRepository('CustomerBundle:TownShip')->findAll();

        return $this->render('CustomerBundle:township:index.html.twig', array(
            'townShips' => $townShips,
            "error" => $error
        ));
    }

    /**
     * Creates a new townShip entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $townShip = new TownShip();
        $form = $this->createForm(TownShipType::class, $townShip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($townShip);
            $em->flush();

            return $this->redirectToRoute('corporation_group_index');
        }

        return $this->render('CustomerBundle:township:new.html.twig', array(
            'townShip' => $townShip,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a townShip entity.
     * @param TownShip $townShip
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(TownShip $townShip)
    {
        $deleteForm = $this->createDeleteForm($townShip);

        return $this->render('CustomerBundle:township:show.html.twig', array(
            'townShip' => $townShip,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing townShip entity.
     * @param Request          $request
     * @param TownShip $townShip
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, TownShip $townShip)
    {
        $deleteForm = $this->createDeleteForm($townShip);
        $editForm = $this->createForm(TownShipType::class, $townShip);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('corporation_group_index');
        }

        return $this->render('CustomerBundle:township:edit.html.twig', array(
            'townShip' => $townShip,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a townShip entity.
     * @param Request          $request
     * @param TownShip $townShip
     * @return \Symfony\Component\HttpFoundation\RedirectResponse | Response
     */
    public function deleteAction(Request $request, TownShip $townShip)
    {
        $form = $this->createDeleteForm($townShip);

        if ($request->isXmlHttpRequest()) {
            return $this->render(":common:remove_object_modal.html.twig",
                [
                    "delete_form" => $form->createView(),
                    "object_title" => $townShip,
                    "default" => false
                ]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($townShip);
            $em->flush();
        }

        return $this->redirectToRoute('corporation_group_index');
    }

    /**
     * Creates a form to delete a townShip entity.
     *
     * @param TownShip $townShip The townShip entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(TownShip $townShip)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('corporation_group_delete', array('slug' => $townShip->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * @param Request $request
     * @param TownShip $equipment
     * @return StreamedResponse
     */
    public function exportTownShipAction(Request $request, TownShip $equipment){

        $response = $this->get("object.eximportdatas")->export('admin_export_township', $equipment)->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllTownShipAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("admin_export_township","CustomerBundle:TownShip", "Corporation Groups" )->prepare($request);

        return $response;
    }
}
