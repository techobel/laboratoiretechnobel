<?php

namespace Tec\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    public function indexAction()
    {
        return $this->render('TecUserBundle::index.html.twig');
    }
    
    public function contactAction()
    {
        return $this->render('TecUserBundle::contact.html.twig');
    }
    
    public function howAction()
    {
        return $this->render('TecUserBundle::how.html.twig');
    }
    
    /********************************
     *         Public profile       *
     ********************************/
    /*
     * Récupère le user qui possède l'id id et affiche son profil
     */
    public function profileAction($id){
        //Récupère le repository de user
        $repository = $this->getDoctrine()->getManager()->getRepository('TecUserBundle:User');
        //Récupère l'user qui possède l'id $id
        $user = $repository->find($id);
        //Test si le user existe
        if($user === null){
            throw new NotFoundHttpException("Le user n'existe pas");  //genere une exception
        }
        //Renvoie vers la page qui affiche la sous categorie
        return $this->render('TecUserBundle::profile.html.twig', array('user' => $user));
    }
}
