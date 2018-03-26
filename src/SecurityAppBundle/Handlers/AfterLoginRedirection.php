<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 24/01/2018
 * Time: 20:03
 */

namespace SecurityAppBundle\Handlers;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AfterLoginRedirection implements AuthenticationSuccessHandlerInterface
{
    protected $router;
    protected $security;

    /**
     * AfterLoginRedirection constructor.
     * @param RouterInterface               $router
     * @param AuthorizationCheckerInterface $security
     */
    public function __construct(RouterInterface $router, AuthorizationCheckerInterface $security)
    {
        $this->router = $router;
        $this->security = $security;
    }

    /**
     * @param Request        $request
     * @param TokenInterface $token
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $response = new RedirectResponse($this->router->generate('admin_homepage'));
        }
        else if ($this->security->isGranted('ROLE_USER')) {
            $response = new RedirectResponse($this->router->generate('homepage_public'));
        } else {
            $referer_url = $request->headers->get('referer');

            $response = new RedirectResponse($referer_url);
        }
        return $response;
    }
}