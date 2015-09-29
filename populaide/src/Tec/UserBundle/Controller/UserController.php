<?php

namespace Tec\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('TecUserBundle:Default:index.html.twig', array('name' => $name));
    }
    
    public function contactAction()
    {
        return $this->render('TecUserBundle::contact.html.twig');
    }
}
