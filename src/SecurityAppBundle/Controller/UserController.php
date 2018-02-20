<?php

namespace SecurityAppBundle\Controller;

use SecurityAppBundle\Entity\User;
use SecurityAppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * User controller.
 *
 */
class UserController extends Controller
{
    /**
     * Lists all user entities.
     *
     * @Route("/all", name="user_index", host="%casemanager_admin_url%")
     * @Method("GET")
     *
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
                $deserialize = $this->get('object.eximportdatas')->import("admin_export_user", $jsonDatas, "AppBundle\Entity\User");

                $error = $deserialize;
            }else{
                $error = "You must provide a file!";
            }
        }

        $users = $em->getRepository('SecurityAppBundle:User')->findAll();

        return $this->render('SecurityAppBundle:user:index.html.twig', array(
            'users' => $users,
            'error' => $error
        ));
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/new", name="user_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('SecurityAppBundle:user:new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/{slug}", name="user_show")
     * @Method("GET")
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);

        return $this->render('SecurityAppBundle:user:show.html.twig', array(
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Method({"GET", "POST", "PATCH"})
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm(UserType::class, $user, ["MODE_CREATE" => false]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('SecurityAppBundle:user:edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a user entity.
     *
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param User $user The user entity     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('slug' => $user->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * @param Request $request
     * @param User $user
     * @return StreamedResponse
     */
    public function exportUserAction(Request $request, User $user){

        $response = $this->get("object.eximportdatas")->export('admin_export_user', $user)->prepare($request);

        return $response;
    }

    /**
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllUserAction(Request $request){
        $response = $this->get("object.eximportdatas")->exportAll("admin_export_user","SecurityAppBundle:User", "Users" )->prepare($request);

        return $response;
    }
}
