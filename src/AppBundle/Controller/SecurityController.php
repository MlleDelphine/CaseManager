<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
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
