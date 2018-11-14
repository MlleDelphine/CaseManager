<?php

namespace BusinessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BusinessBundle:Default:index.html.twig');
    }
}
