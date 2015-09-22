<?php

namespace Tec\ServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('TecServiceBundle:Default:index.html.twig', array('name' => $name));
    }
}
