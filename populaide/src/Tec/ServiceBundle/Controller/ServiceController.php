<?php

namespace Tec\ServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class ServiceController extends Controller
{
    public function indexAction()
    {
        return $this->render('TecServiceBundle:Default:index.html.twig');
    }
    
    public function resultsAction()
    {
        return $this->render('TecServiceBundle::results.html.twig');
    }
}