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
	
    /**
     * 
     * @param type $commentaire
     * @param type $id  
     * id = l'identifiant de l'utilisateur
     * Ajout d'une notification
     */
    public function addNotification($commentaire, $id){
        $notification = new Notification();
        $notification->setCommentaire($commentaire);
        $notification->setDateCreate(new \DateTime());
        $notification->setVue(false);
        //Récupère le repository user
        $repository = $this->getDoctrine()->getManager()->getRepository('TecUserBundle:User');
        //Récupère l'user
        $user = $repository->find($id);
        //Test si l'user existe
        if($user === null){
            throw new NotFoundHttpException("L'user n'existe pas.");
        }
        
        $user->addNotification($notification);

        $notification->setUser($user);
        //récupère le manager
        $em = $this->getDoctrine()->getManager();
        //doctrine se charge de notification
        $em->persist($notification);
        //sauvegarde en bd
        $em->flush();		
    }
	
   
    /**
     * 
     * @param type $id
     * @throws NotFoundHttpException
     * Mise a jour d'une notification, change l'attribut vue à true
     */
    public function updateNotificationAction($id){
        //récupère le repository notification
        $repository = $this->getDoctrine()->getManager()->getRepository('TecUserBundle:Notification');
        //Récupère la notification
        $notification = $repository->find($id);
        //test si la notification existe
        if($notification === null){
            throw new NotFoundHttpException("La notification n'existe pas.");
        }
        //Récupère l'utilisateur en session
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        //test si c'est le proprio de la notification
        if($user->getId() === $notification->getUser()->getId()){
            //si la notification existe
            $notification->setVue(true);

            //récupère le manager
            $em = $this->getDoctrine()->getManager();

            //sauvegarde en bd
            $em->flush();		
            
            //redirection vers profil
            return $this->forward('TecUserBundle:User:profile', array('id' => $user->getId()));
            //return $this->render('TecUserBundle::index.html.twig');
        }else{
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException("Limite.");
        }
    }

    /**
     * 
     * @param type $id
     * @throws NotFoundHttpException
     * Supprime une notification
     */
    public function delNotificationAction($id){
        //recupère le repository notification
        $repository = $this->getDoctrine()->getManager()->getRepository('TecUserBundle:Notification');
        //récupère la notification
        $notification = $repository->find($id);
        //test si notification existe
        if($notification === null){
            throw new NotFoundHttpException("La notification n'existe pas");
        }
        //la notification existe
        
        //Récupère l'utilisateur en session
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        //test si c'est le proprio de la notification ou un admin
        if($user->getId() === $notification->getUser()->getId()){
             //Récupère le manager
            $em = $this->getDoctrine()->getManager();
            //Supprime la notification
            $em->remove($notification);
            //sauvegarde en bd
            $em->flush();

            //redirection vers profil
            return $this->forward('TecUserBundle:User:profile', array('id' => $user->getId()));
        }else{
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException("Limite.");
        }
    }
    
}
