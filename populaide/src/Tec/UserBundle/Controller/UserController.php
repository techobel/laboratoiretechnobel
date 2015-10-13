<?php

namespace Tec\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Tec\UserBundle\Entity\Notification;
use Tec\UserBundle\Form\UserType;

use Tec\ServiceBundle\Controller\ServiceController;

class UserController extends Controller
{
    public function indexAction()
    {
        return $this->render('TecUserBundle::index.html.twig');
    }
    
    public function contactAction(Request $request)
    {
        
        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('name', 'text')
            ->add('email', 'email')
            ->add('subject', 'text')
            ->add('message', 'text')
            ->add('valider', 'submit')
        ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            // $data is a simply array with your form fields 
            // like "query" and "category" as defined above.
            //$data = $form->getData();
            
            $subject = $form["subject"]->getData();
            $admin = "tracy.brisfer@gmail.com";
            $content = $form["message"]->getData();
            $fromName = $form["name"]->getData();
            $from = $form["email"]->getData();
            $body = $content . $fromName . $from;
            
            ServiceController::sendMail($subject, $admin, $body);
           $this->addFlash('notice', "Envoie OK");
        }
        
        return $this->render('TecUserBundle::contact.html.twig', array('form' => $form->createView()));
    }
    
    public function howAction()
    {
        return $this->render('TecUserBundle::how.html.twig');
    }
    
    /********************************
     *         Public profil      *
     ********************************/
    /*
     * Récupère le user qui possède l'id id et affiche son profil
     */
    public function getProfilUserAction($id){
        //Récupère le repository de user
        $repository = $this->getDoctrine()->getManager()->getRepository('TecUserBundle:User');
        //Récupère l'user qui possède l'id $id
        $user = $repository->find($id);
        //Test si le user existe
        if($user === null){
            throw new NotFoundHttpException("Le user n'existe pas");  //genere une exception
        }
        //Renvoie vers la page qui affiche la sous categorie
        return $this->render('TecUserBundle::profil.html.twig', array('user' => $user));
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
            return $this->forward('TecUserBundle:User:profil', array('id' => $user->getId()));
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
            return $this->forward('TecUserBundle:User:profil', array('id' => $user->getId()));
        }else{
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException("Limite.");
        }
    }
    
    /*********************************************** 
     * recupère les notifications de l'utilisateur *
     ***********************************************/    
    public function getAllNotificationUserConnect(){
        //Récupère l'utilisateur en session
        $user = $this->container->get('security.context')->getToken()->getUser();
        //Récupère le repository
        $repository = $this->getDoctrine()->getManager()->getRepository('TecUserBundle:User');
        //Récupère toutes les notifications de l'utilisateur
        $notificaiton = $repository->find($user->getId()->getNotifications());
        //retourne les notifications
        return $notificaiton;
    }
    
    /**************************************** 
     * Supprime le user qui possède l'id id *
     ****************************************/
    public function delUserAction($id){
        //On vérifie que l'utilisateur est un admin
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
          // Sinon on déclenche une exception « Accès interdit »
          throw new AccessDeniedException('Accès limité.');
        }
        //Récupère le repository de user
        $repository = $this->getDoctrine()->getManager()->getRepository('TecUserBundle:User');
        //Récupère le user qui possède l'id $id
        $user = $repository->find($id);
        //Si le user n'existe pas
        if($user === null){
            throw new NotFoundHttpException("Le user n'existe pas.");
        }
        //Si le user existe     
        //Récupère le manager de fosuser
        $userManager = $this->container->get('fos_user.user_manager');
        //Suppression de l'utilisateur
        $userManager->deleteUser($user);
        //Récupère l'utilisateur en session
        $userAdmin = $this->container->get('security.context')->getToken()->getUser();
        //Ajout d'une notificatoin l'admin
        UserController::addNotification("Suppression de l'utilisateur ".$user->getId()."OK.", $userAdmin->getId());
        //Suppression
        //$em->flush();
        //redirection vers getAllUser ( a voir)
        //return $this->forward('TecUserBundle:User:getAllUser');
        //Redirection
        return $this->redirect($this->generateUrl('tec_service_admin'));
    }
    
    /*************************
     * Mise a jour d'un user *
     *************************/
    public function updateUserAction(Request $request, $id){
        //Recupère le repository user
        $repository = $this->getDoctrine()->getManager()->getRepository('TecUserBundle:User');
        //Récupère le type à modifier
        $user = $repository->find($id);
        //Si le user n'existe pas
        if($user === null){
            throw new NotFoundHttpException("Le user n'existe pas.");
        }        
        //Récupère l'utilisateur en session
        $usersess = $this->container->get('security.context')->getToken()->getUser();        
        //On vérifie que l'utilisateur est un admin ou l'utilisateur veut modifier son profil
        if ((!$this->get('security.context')->isGranted('ROLE_ADMIN'))&&($usersess->getId() != $user->getId() )) {
          // Sinon on déclenche une exception « Accès interdit »
          throw new AccessDeniedException('Accès limité.');
        }        
                
        //Si le user existe
        //Création du formulaire pour la mise à jour
        $form = $this->get('form.factory')->create(new UserType(), $user);
        //si le formulaire a été valide
        if($form->handleRequest($request)->isValid()){             
            //a voir
            $this->get('fos_user.user_manager')->updateUser($user, false);            
            //Récupère le manager
            $em = $this->getDoctrine()->getManager();
            //Sauvegarde en bdd des updates
            $em->flush();
            //Ajout d'un message flash (a voir)
            $this->addFlash('notice', "Mise a jour OK.");
            //Récupère l'utilisateur en session
            $user = $this->container->get('security.context')->getToken()->getUser();
            //Ajout d'une notificatoin l'admin
            UserController::addNotification("Mise à jour de l'utilisteur  ".$usersess->getName()."OK.", $user->getId());
            //Redirection vers la categorie
            //return $this->forward('TecUserBundle:User:getUser', array('id' => $id));
            //Redirection
            return $this->redirect($this->generateUrl('tec_service_admin'));
        }
        return $this->render('TecUserBundle::updateUser.html.twig', array('form' => $form->createView()));
    }
    
    /********************************* 
     * @param Request $request       *
     * @return type                  *
     * @throws AccessDeniedException *
     *********************************/
    public function addUserAction(Request $request){
        //On vérifie que l'utilisateur est un admin
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
          // Sinon on déclenche une exception « Accès interdit »
          throw new AccessDeniedException('Accès limité.');
        }        
        //Si l'utilisateur est un admin
        
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $this->addFlash('notice', "ajout de l'user ok");
                $url = $this->generateUrl('tec_service_admin');
                $response = new RedirectResponse($url);
            }

            //$dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));
            //Récupère l'utilisateur en session
            $userAdmin = $this->container->get('security.context')->getToken()->getUser();
            //Ajout d'une notificatoin l'admin
            UserController::addNotification("Ajout de l'utilisateur ".$user->getName()."OK.", $userAdmin->getId());
            return $response;
        }
  
        //si le formulaire n'a pas été validé
        return $this->render('TecUserBundle::addUser.html.twig', array('form' => $form->createView()));
    }   
    
    public function getMyProfilAction(){
        //Récupère l'utilisateur en session
        $user = $this->container->get('security.context')->getToken()->getUser();
        return $this->render('TecUserBundle::profil.html.twig', array('user' => $user));
    }
    
    
}
