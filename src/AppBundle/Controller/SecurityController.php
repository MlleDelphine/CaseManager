<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
//    /**
//     * @Sensio\Route("/login", name="casemanager_backend_login")
//     * @param Request $request
//     * @param AuthenticationUtils $authenticationUtils
//     * @return \Symfony\Component\HttpFoundation\Response
//     */
//    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
//    {
//
//      //  $authenticationUtils = $this->get('security.authentication_utils');
//
//        // get the login error if there is one
//        $error = $authenticationUtils->getLastAuthenticationError();
//
//        // last username entered by the user
//        $lastUsername = $authenticationUtils->getLastUsername();
//
//        return $this->render('AppBundle:Security:login.html.twig',
//            array(
//                // last username entered by the user
//                'last_username' => $lastUsername,
//                'error'         => $error,
//            ));
//    }

    /**
     * @Sensio\Route("/logout", name="casemanager_backend_logout")
     */
    public function logoutAction()
    {
    }

    protected function generateToken()
    {
        $token = $this->get('form.csrf_provider')
            ->generateCsrfToken('authenticate');

        return $token;
    }
}
