<?php

namespace Tec\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Tec\UserBundle\Entity\Notification;

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
	
	public function addNotification($commentaire){
		$notification = new Notification();
		$notification->setCommentaire($commentaire);
		//Récupère l'utilisateur en session
        $user = $this->container->get('security.context')->getToken()->getUser();
		$user->addNotification($notification);
		//récupère le manager
		$em = $this->getDoctrine()->getManager();
		//doctrine se charge de notification
		$em->persist($notification);
		//sauvegarde en bd
		$em->flush();		
	}
	
	public function updateNotification($id){
		//récupère le repository notification
		$repository = $this->getDoctrine()->getManager->getRepository('TecUserBundle:Notification');
		//Récupère la notification
		$notification = $repository->find($id);
		//test si la notification existe
		if($notification === null){
				throw new NotFoundHttpException("La notification n'existe pas.");
		}
		//si la notification existe
		$notification->setVue(true);
		
		//récupère le manager
		$em = $this->getDoctrine()->getManager();
		
		//sauvegarde en bd
		$em->flush();		
	}
	
	public function delNotification($id){
		//recupère le repository notification
		$repository = $this->getDoctrine()->getManager()->getRepository('TecUserBundle:Notification');
		//récupère la notification
		$notification = $repository->find($id);
		//test si notification existe
		if($notification === null){
			throw new NotFoundHttpException("La notification n'existe pas");
		}
		//la notification existe
		
	}
	
	
}
