<?php

namespace Tec\ServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Tec\ServiceBundle\Entity\Annonce;
use Tec\ServiceBundle\Entity\Categorie;
use Tec\ServiceBundle\Entity\Sub_categorie;
use Tec\ServiceBundle\Entity\Type;

use Tec\UserBundle\Entity\User;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Tec\ServiceBundle\Form\AnnonceType;
use Tec\ServiceBundle\Form\CategorieType;
use Tec\ServiceBundle\Form\Sub_categorieType;
use Tec\ServiceBundle\Form\TypeType;

use Tec\UserBundle\Form\UserType;
//
//class DefaultController extends Controller
//{
//    public function indexAction()
//    {
//        return $this->render('TecServiceBundle:Default:index.html.twig');
//    }
//}


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
    
    /****************************
     *          ANNONCE         *
     ****************************/
    
    /**
     * Ajout d'une annonce
     */
    public function addAnnonceAction(Request $request){
        //On vérifie que l'utilisateur est connecté
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
          // Sinon on déclenche une exception « Accès interdit »
          throw new AccessDeniedException('Accès limité.');
        }  
        //Si l'utilisateur est connecté
        //Création de l'article
        $annonce = new Annonce();
        
        //Création du formulaire
        $form = $this->get('form.factory')->create(new AnnonceType(), $annonce);
        //Si le formulaire a été validé
        if($form->handleRequest($request)->isValid()){
            //Récupère l'utilisateur en session
            $user = $this->container->get('security.context')->getToken()->getUser();
            //Récupère le manager
            $em = $this->getDoctrine()->getManager();
            //Initialisation des attributs = date création/active article
            $annonce->setActive(true);
            $annonce->setCreationDate(new \DateTime());           
            //Récupère la subcategorie
            $subcategorie = $form->get('sub_categorie')->getData();            
            //Relation annonce - user - sub_categorie
            $annonce->setSubCategorie($subcategorie);
            $subcategorie->addAnnonce($annonce);    
            //$user->
            //Doctrine se charge de l'entity annonce
            $em->persist($annonce);
            //Sauvegarde en bd
            $em->flush();
            //Ajout d'un message flash
            $this->addFlash('notice', "Ajout annonce OK.");
            //Redirection
            return $this->forward('TecServiceBundle:Service:results');
        }        
        //si le formulaire n'a pas été validé
        return $this->render('TecServiceBundle::test.html.twig', array('form' => $form->createView()));
    }
    
    /**
     * Récupère les annonces de la BD
     */
    public function getAllAnnonceAction(){
        //On vérifie que l'utilisateur est un admin
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
          // Sinon on déclenche une exception « Accès interdit »
          throw new AccessDeniedException('Accès limité.');
        }
        //Récupère le repository de annonce
        $repository = $this->getDoctrine()->getManager()->getRepository('TecServiceBundle:Annonce');
        //Récupère toutes les annonces de la bd
        $annonces = $repository->findAll();
        //Renvoie vers la page qui affiche toutes les annonces
        return $this->render('TecServiceBundle::getAllAnnonce.html.twig', array('annonces' => $annonces));
    }
    
    /**
     * 
     * Récupère l'annonce qui possède l'id id
     */
    public function getAnnonceAction($id){
        //On vérifie les droits de l'utilisateur (a voir qui peut voir l'annonce
        //..
        //Récupère le repository de annonce
        $repository = $this->getDoctrine()->getManager()->getRepository('TecServiceBundle:Annonce');
        //Récupère l'annonce qui possède l'id $id
        $annonce = $repository->find($id);
        //Test si l'annonce existe
        if($annonce === null){
            throw new NotFoundHttpException("L'annonce n'existe pas");  //genere une exception
        }
        //Renvoie vers la page qui affiche l'annonce
        return $this->render('TecServiceBundle::getAnnonce.html.twig', array('annonce' => $annonce));
    }
    
    /**
     * 
     * Supprime l'annonce qui possède l'id id
     */
    public function delAnnonceAction($id){
        //On vérifie que l'utilisateur est connecté
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
          // Sinon on déclenche une exception « Accès interdit »
          throw new AccessDeniedException('Accès limité.');
        }
        
        //Test si l'utilisateur possède l'annonce ou si c'est un admin
        //......
        
        
       
        //Récupère le repository de annonce
        $repository = $this->getDoctrine()->getManager()->getRepository('TecServiceBundle:Annonce');
        //Récupère l'annonce qui possède l'id $id
        $annonce = $repository->find($id);
        //Si l'annonce n'existe pas
        if($annonce === null){
            throw new NotFoundHttpException("L'annonce n'existe pas.");
        }
        //Si l'annonce existe
        //Récupère le manager
        $em = $this->getDoctrine()->getManager();
        //Suppression de l'annonce
        $em->remove($annonce);
        //Suppression
        $em->flush();
        //redirection vers getAllAnnonce ( a voir)
        return $this->forward('TecServiceBundle:Service:getAllAnnonce');
    }
    
    /**
     * Mise a jour d'une annonce
     */
    public function updateAnnonceAction(Request $request, $id){
        //On vérifie que l'utilisateur est connecté
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
          // Sinon on déclenche une exception « Accès interdit »
          throw new AccessDeniedException('Accès limité.');
        }
        
        //Test si l'user possède l'annonce ou si c'est un admin
        //...
        
        
        //Recupère le repository annonce
        $repository = $this->getDoctrine()->getManager()->getRepository('TecServiceBundle:Annonce');
        //Récupère l'annonce à modifier
        $annonce = $repository->find($id);
        //Si l'annonce n'existe pas
        if($annonce === null){
            throw new NotFoundHttpException("L'annonce n'existe pas.");
        }
        //Si l'annonce existe
        //Création du formulaire pour la mise à jour
        $form = $this->get('form.factory')->create(new AnnonceType(), $annonce);
        //si le formulaire a été valide
        if($form->handleRequest($request)->isValid()){
            //Récupère le manager
            $em = $this->getDoctrine()->getManager();
            //MISE A JOUR DES RELATION A FAIRE
            
            
            //....
            
            //
            
            //Sauvegarde en bdd des updates
            $em->flush();
            //Ajout d'un message flash (a voir)
            //$this->addFlash('notice', "Mise a jour OK.");
            //Redirection vers l'annonce
            //a tester redirection avec parametre
            $this->getRequest()->setParameter('id', $id);
            return $this->forward('TecServiceBundle:Service:getAnnonce');
        }
        return $this->render('TecServiceBundle::updateAnnonce.html.twig', array('form' => $form -> createView()));
    }
    
    /************************
     *      CATEGORIE       *
     ************************/
    
    /**
     * Ajout d'une categorie
     */
    public function addCategorieAction(Request $request){
        //On vérifie que l'utilisateur est un admin
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
          // Sinon on déclenche une exception « Accès interdit »
          throw new AccessDeniedException('Accès limité.');
        }
        //Si l'utilisateur est connecté
        //Création de la categorie
        $categorie = new Categorie();
        
        //Création du formulaire
        $form = $this->get('form.factory')->create(new CategorieType(), $categorie);
        //Si le formulaire a été validé
        if($form->handleRequest($request)->isValid()){   
            
            
            //Gestion de l'image
            
            
            //Récupère le manager
            $em = $this->getDoctrine()->getManager();    
            //Doctrine se charge de l'entity categorie
            $em->persist($categorie);
            //Sauvegarde en bd
            $em->flush();
            //Ajout d'un message flash
            $this->addFlash('notice', "Ajout categorie OK.");
            //Redirection (a voir)
            return $this->forward('TecServiceBundle:Service:results');
        }        
        //si le formulaire n'a pas été validé
        return $this->render('TecServiceBundle::addCategorie.html.twig', array('form' => $form->createView()));
    }
    
    /**
     * Récupère les categories de la BD
     */
    public function getAllCategorieAction(){
        //On vérifie les droits de l'utilisateur (a voir qui peut voir toutes les categorie
        //..
        //Récupère le repository de annonce
        $repository = $this->getDoctrine()->getManager()->getRepository('TecServiceBundle:Categorie');
        //Récupère toutes les categories de la bd
        $categories = $repository->findAll();
        //Renvoie vers la page qui affiche toutes les categories
        return $this->render('TecServiceBundle::getAllCategorie.html.twig', array('categories' => $categories));
    }
    
    /**
     * 
     * Récupère la categorie qui possède l'id id
     */
    public function getCategorieAction($id){
        //On vérifie les droits de l'utilisateur (a voir qui peut voir la categorie
        //..
        //Récupère le repository de categorie
        $repository = $this->getDoctrine()->getManager()->getRepository('TecServiceBundle:Categorie');
        //Récupère la categorie qui possède l'id $id
        $categorie = $repository->find($id);
        //Test si la categorie existe
        if($categorie === null){
            throw new NotFoundHttpException("La categorie n'existe pas");  //genere une exception
        }
        //Renvoie vers la page qui affiche la categorie
        return $this->render('TecServiceBundle::getCategorie.html.twig', array('categorie' => $categorie));
    }
    
    /**
     * 
     * Supprime la categorie qui possède l'id id
     */
    public function delCategorieAction($id){
        //On vérifie que l'utilisateur est un admin
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
          // Sinon on déclenche une exception « Accès interdit »
          throw new AccessDeniedException('Accès limité.');
        }
        //Récupère le repository de Categorie
        $repository = $this->getDoctrine()->getManager()->getRepository('TecServiceBundle:Categorie');
        //Récupère la categorie qui possède l'id $id
        $categorie = $repository->find($id);
        //Si la categorie n'existe pas
        if($categorie === null){
            throw new NotFoundHttpException("La categorie n'existe pas.");
        }
        //Si la categorie existe
    
        //Gestion de l'image
        
        
        //Récupère le manager
        $em = $this->getDoctrine()->getManager();
        //Suppression de la categorie
        $em->remove($categorie);
        //Suppression
        $em->flush();
        //redirection vers getAllCategorie ( a voir)
        return $this->forward('TecServiceBundle:Service:getAllCategorie');
    }
    
    /**
     * Mise a jour d'une categorie
     */
    public function updateCategorieAction(Request $request, $id){
        //On vérifie que l'utilisateur est un admin
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
          // Sinon on déclenche une exception « Accès interdit »
          throw new AccessDeniedException('Accès limité.');
        }
        //Recupère le repository categorie
        $repository = $this->getDoctrine()->getManager()->getRepository('TecServiceBundle:Categorie');
        //Récupère la categorie à modifier
        $categorie = $repository->find($id);
        //Si la categorie n'existe pas
        if($categorie === null){
            throw new NotFoundHttpException("La categorie n'existe pas.");
        }
        //Si la categorie existe
        //Création du formulaire pour la mise à jour
        $form = $this->get('form.factory')->create(new CategorieType(), $categorie);
        //si le formulaire a été valide
        if($form->handleRequest($request)->isValid()){
            
            
            //Gestion de l'image
            
            //Récupère le manager
            $em = $this->getDoctrine()->getManager();
            //Sauvegarde en bdd des updates
            $em->flush();
            //Ajout d'un message flash (a voir)
            //$this->addFlash('notice', "Mise a jour OK.");
            //Redirection vers la categorie
            //a tester redirection avec parametre
            $this->getRequest()->setParameter('id', $id);
            return $this->forward('TecServiceBundle:Service:getCategorie');
        }
        return $this->render('TecServiceBundle::updateCategorie.html.twig', array('form' => $form->createView()));
    }
    
    /************************
     *      SUB_CATEGORIE       *
     ************************/
    
    /**
     * Ajout d'une sous categorie
     */
    public function addSubCategorieAction(Request $request){
        //On vérifie que l'utilisateur est un admin
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
          // Sinon on déclenche une exception « Accès interdit »
          throw new AccessDeniedException('Accès limité.');
        }
        //Si l'utilisateur est connecté
        //Création de la sous categorie
        $subcategorie = new Sub_categorie();
        
        //Création du formulaire
        $form = $this->get('form.factory')->create(new Sub_categorieType(), $subcategorie);
        //Si le formulaire a été validé
        if($form->handleRequest($request)->isValid()){   
            //Récupère le manager
            $em = $this->getDoctrine()->getManager();    
            //Doctrine se charge de l'entity categorie
            $em->persist($subcategorie);
            //Sauvegarde en bd
            $em->flush();
            //Ajout d'un message flash
            $this->addFlash('notice', "Ajout sous categorie OK.");
            //Redirection (a voir)
            return $this->forward('TecServiceBundle:Service:results');
        }        
        //si le formulaire n'a pas été validé
        return $this->render('TecServiceBundle::addSubCategorie.html.twig', array('form' => $form->createView()));
    }
    
    /**
     * Récupère les sous categories de la BD
     */
    public function getAllSubCategorieAction(){
        //On vérifie les droits de l'utilisateur (a voir qui peut voir toutes les sous categorie
        //..
        //Récupère le repository de sub_categorie
        $repository = $this->getDoctrine()->getManager()->getRepository('TecServiceBundle:Sub_categorie');
        //Récupère les sous categories de la bd
        $subcategories = $repository->findAll();
        //Renvoie vers la page qui affiche toutes les sous categories
        return $this->render('TecServiceBundle::getAllSubCategorie.html.twig', array('subcategories' => $subcategories));
    }
    
    /**
     * 
     * Récupère la sous categorie qui possède l'id id
     */
    public function getSubCategorieAction($id){
        //On vérifie les droits de l'utilisateur (a voir qui peut voir la sous categorie
        //..
        //Récupère le repository de sub_categorie
        $repository = $this->getDoctrine()->getManager()->getRepository('TecServiceBundle:Sub_categorie');
        //Récupère la sous categorie qui possède l'id $id
        $subcategorie = $repository->find($id);
        //Test si la sous categorie existe
        if($subcategorie === null){
            throw new NotFoundHttpException("La sous categorie n'existe pas");  //genere une exception
        }
        //Renvoie vers la page qui affiche la sous categorie
        return $this->render('TecServiceBundle::getSubCategorie.html.twig', array('subcategorie' => $subcategorie));
    }
    
    /**
     * 
     * Supprime la sous categorie qui possède l'id id
     */
    public function delSubCategorieAction($id){
        //On vérifie que l'utilisateur est un admin
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
          // Sinon on déclenche une exception « Accès interdit »
          throw new AccessDeniedException('Accès limité.');
        }
        //Récupère le repository de sous categorie
        $repository = $this->getDoctrine()->getManager()->getRepository('TecServiceBundle:Sub_categorie');
        //Récupère la categorie qui possède l'id $id
        $subcategorie = $repository->find($id);
        //Si la categorie n'existe pas
        if($subcategorie === null){
            throw new NotFoundHttpException("La sous categorie n'existe pas.");
        }
        //Si la categorie existe
     
        //Récupère le manager
        $em = $this->getDoctrine()->getManager();
        //Suppression de la categorie
        $em->remove($subcategorie);
        //Suppression
        $em->flush();
        //redirection vers getAllCategorie ( a voir)
        return $this->forward('TecServiceBundle:Service:getAllCategorie');
    }
    
    /**
     * Mise a jour d'une sous categorie
     */
    public function updateSubCategorieAction(Request $request, $id){
        //On vérifie que l'utilisateur est un admin
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
          // Sinon on déclenche une exception « Accès interdit »
          throw new AccessDeniedException('Accès limité.');
        }
        //Recupère le repository sous categorie
        $repository = $this->getDoctrine()->getManager()->getRepository('TecServiceBundle:Sub_categorie');
        //Récupère la sous categorie à modifier
        $subcategorie = $repository->find($id);
        //Si la sous categorie n'existe pas
        if($subcategorie === null){
            throw new NotFoundHttpException("La sous categorie n'existe pas.");
        }
        //Si la sous categorie existe
        //Création du formulaire pour la mise à jour
        $form = $this->get('form.factory')->create(new Sub_categorieType(), $subcategorie);
        //si le formulaire a été valide
        if($form->handleRequest($request)->isValid()){
 
            //Récupère le manager
            $em = $this->getDoctrine()->getManager();
            //Sauvegarde en bdd des updates
            $em->flush();
            //Ajout d'un message flash (a voir)
            //$this->addFlash('notice', "Mise a jour OK.");
            //Redirection vers la categorie
            //a tester redirection avec parametre
            $this->getRequest()->setParameter('id', $id);
            return $this->forward('TecServiceBundle:Service:getSubCategorie');
        }
        return $this->render('TecServiceBundle::updateSubCategorie.html.twig', array('form' => $form->createView()));
    }
    
    /************************
     *      TYPE       *
     ************************/
    
    /**
     * Ajout d'un type
     */
    public function addTypeAction(Request $request){
        //On vérifie que l'utilisateur est un admin
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
          // Sinon on déclenche une exception « Accès interdit »
          throw new AccessDeniedException('Accès limité.');
        }
        //Si l'utilisateur est connecté
        //Création du type
        $type = new Type();
        
        //Création du formulaire
        $form = $this->get('form.factory')->create(new TypeType(), $type);
        //Si le formulaire a été validé
        if($form->handleRequest($request)->isValid()){   
            //Récupère le manager
            $em = $this->getDoctrine()->getManager();    
            //Doctrine se charge de l'entity type
            $em->persist($type);
            //Sauvegarde en bd
            $em->flush();
            //Ajout d'un message flash
            $this->addFlash('notice', "Ajout d'un type OK.");
            //Redirection (a voir)
            return $this->forward('TecServiceBundle:Service:results');
        }        
        //si le formulaire n'a pas été validé
        return $this->render('TecServiceBundle::addType.html.twig', array('form' => $form->createView()));
    }
    
    /**
     * Récupère les type de la BD
     */
    public function getAllTypeAction(){
        //On vérifie les droits de l'utilisateur (a voir qui peut voir les types de la bdd
        //..
        //Récupère le repository de type
        $repository = $this->getDoctrine()->getManager()->getRepository('TecServiceBundle:Type');
        //Récupère les type de la bd
        $types = $repository->findAll();
        //Renvoie vers la page qui affiche les types
        return $this->render('TecServiceBundle::getAllType.html.twig', array('types' => $types));
    }
    
    /**
     * 
     * Récupère le type qui possède l'id id
     */
    public function getTypeAction($id){
        //On vérifie les droits de l'utilisateur (a voir qui peut voir le type
        //..
        //Récupère le repository de type
        $repository = $this->getDoctrine()->getManager()->getRepository('TecServiceBundle:Type');
        //Récupère le type qui possède l'id $id
        $type = $repository->find($id);
        //Test si le type existe
        if($type === null){
            throw new NotFoundHttpException("Le type n'existe pas");  //genere une exception
        }
        //Renvoie vers la page qui affiche la sous categorie
        return $this->render('TecServiceBundle::getType.html.twig', array('type' => $type));
    }
    
    /**
     * 
     * Supprime le type qui possède l'id id
     */
    public function delTypeAction($id){
        //On vérifie que l'utilisateur est un admin
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
          // Sinon on déclenche une exception « Accès interdit »
          throw new AccessDeniedException('Accès limité.');
        }
        //Récupère le repository de type
        $repository = $this->getDoctrine()->getManager()->getRepository('TecServiceBundle:Type');
        //Récupère le type qui possède l'id $id
        $type = $repository->find($id);
        //Si la categorie n'existe pas
        if($type === null){
            throw new NotFoundHttpException("Le type n'existe pas.");
        }
        //Si le type existe
     
        //Récupère le manager
        $em = $this->getDoctrine()->getManager();
        //Suppression du type
        $em->remove($type);
        //Suppression
        $em->flush();
        //redirection vers getAllType ( a voir)
        return $this->forward('TecServiceBundle:Service:getAllType');
    }
    
    /**
     * Mise a jour d'un type
     */
    public function updateTypeAction(Request $request, $id){
        //On vérifie que l'utilisateur est un admin
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
          // Sinon on déclenche une exception « Accès interdit »
          throw new AccessDeniedException('Accès limité.');
        }
        //Recupère le repository type
        $repository = $this->getDoctrine()->getManager()->getRepository('TecServiceBundle:Type');
        //Récupère le type à modifier
        $type = $repository->find($id);
        //Si le type n'existe pas
        if($type === null){
            throw new NotFoundHttpException("Le type n'existe pas.");
        }
        //Si le type existe
        //Création du formulaire pour la mise à jour
        $form = $this->get('form.factory')->create(new TypeType(), $type);
        //si le formulaire a été valide
        if($form->handleRequest($request)->isValid()){
 
            //Récupère le manager
            $em = $this->getDoctrine()->getManager();
            //Sauvegarde en bdd des updates
            $em->flush();
            //Ajout d'un message flash (a voir)
            //$this->addFlash('notice', "Mise a jour OK.");
            //Redirection vers la categorie
            //a tester redirection avec parametre
            $this->getRequest()->setParameter('id', $id);
            return $this->forward('TecServiceBundle:Service:getType');
        }
        return $this->render('TecServiceBundle::updateType.html.twig', array('form' => $form->createView()));
    }
    
    /************************
     *      USER       *
     ************************/
    
    /**
     * Récupère les user de la BD
     */
    public function getAllUserAction(){
        //On vérifie les droits de l'utilisateur (a voir qui peut voir les users de la bdd
        //..
        //Récupère le repository de user
        $repository = $this->getDoctrine()->getManager()->getRepository('TecUserBundle:User');
        //Récupère les type de la bd
        $users = $repository->findAll();
        //Renvoie vers la page qui affiche les users
        return $this->render('TecServiceBundle::getAllUser.html.twig', array('users' => $users));
    }
    
    /**
     * 
     * Récupère le user qui possède l'id id
     */
    public function getUserAction($id){
        //On vérifie les droits de l'utilisateur (a voir qui peut voir l'user
        //..
        //Récupère le repository de user
        $repository = $this->getDoctrine()->getManager()->getRepository('TecUserBundle:User');
        //Récupère l'user qui possède l'id $id
        $user = $repository->find($id);
        //Test si le user existe
        if($user === null){
            throw new NotFoundHttpException("Le user n'existe pas");  //genere une exception
        }
        //Renvoie vers la page qui affiche la sous categorie
        return $this->render('TecServiceBundle::getUser.html.twig', array('user' => $user));
    }
    
    /**
     * 
     * Supprime le user qui possède l'id id
     */
    public function delUserAction($id){
        //On vérifie les droits de l'utilisateur (a voir qui peut supprimer le user)
        //..
        //Récupère le repository de user
        $repository = $this->getDoctrine()->getManager()->getRepository('TecUserBundle:User');
        //Récupère le user qui possède l'id $id
        $user = $repository->find($id);
        //Si le user n'existe pas
        if($user === null){
            throw new NotFoundHttpException("Le user n'existe pas.");
        }
        //Si le user existe
     
        //Récupère le manager
        $em = $this->getDoctrine()->getManager();
        //Suppression du type
        $em->remove($user);
        //Suppression
        $em->flush();
        //redirection vers getAllUser ( a voir)
        return $this->forward('TecServiceBundle:Service:getAllUser');
    }
    
    /**
     * Mise a jour d'un user
     */
    public function updateUserAction(Request $request, $id){
        //On vérifie les droits de l'utilisateur (a voir qui peut mettre à jour les users
        //..
        //Recupère le repository user
        $repository = $this->getDoctrine()->getManager()->getRepository('TecUserBundle:User');
        //Récupère le type à modifier
        $user = $repository->find($id);
        //Si le user n'existe pas
        if($user === null){
            throw new NotFoundHttpException("Le user n'existe pas.");
        }
        //Si le user existe
        //Création du formulaire pour la mise à jour
        $form = $this->get('form.factory')->create(new UserType(), $user);
        //si le formulaire a été valide
        if($form->handleRequest($request)->isValid()){
 
            //Récupère le manager
            $em = $this->getDoctrine()->getManager();
            //Sauvegarde en bdd des updates
            $em->flush();
            //Ajout d'un message flash (a voir)
            //$this->addFlash('notice', "Mise a jour OK.");
            //Redirection vers la categorie
            //a tester redirection avec parametre
            $this->getRequest()->setParameter('id', $id);
            return $this->forward('TecServiceBundle:Service:getUser');
        }
        return $this->render('TecServiceBundle::updateUser.html.twig', array('form' => $form->createView()));
    }
    
}
