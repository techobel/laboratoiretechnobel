<?php

namespace Tec\ServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\Query\ResultSetMapping;

use Tec\ServiceBundle\Entity\Annonce;
use Tec\ServiceBundle\Entity\Categorie;
use Tec\ServiceBundle\Entity\Sub_categorie;
use Tec\ServiceBundle\Entity\Type;
use Tec\ServiceBundle\Entity\Postuler;
use Tec\UserBundle\Entity\Demander;
use Tec\UserBundle\Entity\Fournir;
use Tec\ServiceBundle\Entity\Service;
use Tec\ServiceBundle\Entity\Media;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Process\Exception\LogicException;

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
    
    /**
     * 
     * action pour test envoie mail
     */
    public function mailAction(){
        $message = \Swift_Message::newInstance()
                ->setSubject('Test mail')
                ->setFrom('technobellaboratoire@outlook.fr')
                ->setTo("hbenji@hotmail.com")
                ->setContentType('text/html')
                ->setBody("testmail");
        
        var_dump($this->get('mailer')->send($message));

        return $this->render('TecServiceBundle::results.html.twig');
    }
    
    /**
     * 
     * @param $subject  sujet du mail ('Inscription chez Popul'aide', 'Compte bloqué', ...)
     * @param $to   email de l'utilisateur 
     * @param $body corps de l'email
     * @return boolean
     */
    public function sendMail($subject, $to, $body){
        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom('technobellaboratoire@outlook.fr')
                ->setTo($to)
                ->setContentType('text/html')
                ->setBody($body);
        
        return $this->get('mailer')->send($message);
        
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
        //Ajout de la class du formulaire
        $options = array('attr' => array('class' => 'form row form-inline clearfix'));        
        //Création du formulaire
        $form = $this->get('form.factory')->create(new AnnonceType(), $annonce, $options);
        //Si le formulaire a été validé
        if($form->handleRequest($request)->isValid()){
            //Récupère l'utilisateur en session
            $user = $this->container->get('security.context')->getToken()->getUser();
            //Récupère le manager
            $em = $this->getDoctrine()->getManager();
            //Initialisation des attributs = date création/active article
            $annonce->setActive(true);
            $annonce->setCreationDate(new \DateTime()); 
            
            /**
             * Relation automatique subcategorie - annonce, type - annonce 
             * donc pas besoin de faire ce qu'il y a en commentaire ci-dessous
             */
            /*
            Récupère la subcategorie
            $subcategorie = $form->get('sub_categorie')->getData(); 
            Récupère le type
            $type = $form->get('type')->getData();
            Relation annonce - user - sub_categorie
            
            $annonce->setSubCategorie($subcategorie);
            $subcategorie->addAnnonce($annonce);
            
            $type->addAnnonce($annonce);
            $annonce->setType($type);
            */
            $user->addAnnonce($annonce);
            $annonce->setUser($user);
            
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

        //Récupère le repository de annonce
        $repository = $this->getDoctrine()->getManager()->getRepository('TecServiceBundle:Annonce');
        //Récupère l'annonce qui possède l'id $id
        $annonce = $repository->find($id);
        //Si l'annonce n'existe pas
        if($annonce === null){
            throw new NotFoundHttpException("L'annonce n'existe pas.");
        }
        //Si l'annonce existe

        //Récupère l'utilisateur en session
        $user = $this->container->get('security.context')->getToken()->getUser();
        //Test si l'utilisateur possède l'annonce ou si c'est un admin
        if(($this->get('security.context')->isGranted('ROLE_ADMIN'))||($annonce->getUser()->getId() === $user->getId())){
            //Traitement
            //Récupère le manager
            $em = $this->getDoctrine()->getManager();
            //Suppression de l'annonce
            $em->remove($annonce);
            //Suppression
            $em->flush();
            //redirection vers getAllAnnonce ( a voir)
            return $this->forward('TecServiceBundle:Service:getAllAnnonce');
        }else{
          throw new AccessDeniedException("Vous n'avez pas les droits sur cette annonce.");
        }
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

        //Recupère le repository annonce
        $repository = $this->getDoctrine()->getManager()->getRepository('TecServiceBundle:Annonce');
        //Récupère l'annonce à modifier
        $annonce = $repository->find($id);
        //Si l'annonce n'existe pas
        if($annonce === null){
            throw new NotFoundHttpException("L'annonce n'existe pas.");
        }
        //Si l'annonce existe

        //Récupère l'utilisateur en session
        $user = $this->container->get('security.context')->getToken()->getUser();
        //Test si l'utilisateur possède l'annonce ou si c'est un admin
        if(($this->get('security.context')->isGranted('ROLE_ADMIN'))||($annonce->getUser()->getId() === $user->getId())){
            //Traitement
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
        }else{
          throw new AccessDeniedException("Vous n'avez pas les droits sur cette annonce.");
        }
    }
    
    /**
     * 
     * @param type $id
     * Permet à un utilisateur de postuler à une annonce
     */
    public function postulerAnnonceAction($id){
        //On vérifie que l'utilisateur est connecté
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
          // Sinon on déclenche une exception « Accès interdit »
          throw new AccessDeniedException('Accès limité.');
        }       
        //récupère le repository
        $repository = $this->getDoctrine()->getManager()->getRepository('TecServiceBundle:Annonce');
        //Récupère l'annonce qui possède l'id $id
        $annonce = $repository->find($id);
        //test si l'annonce existe
        if($annonce === null){
            throw new NotFoundHttpException("L'annonce n'existe pas");
        }
        //si l'annonce existe
        
        //Récupère l'utilisateur en session
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        //test si l'utilisateur possède l'annonce
        if($user->getId() === $annonce->getUser()->getId()){
            throw new LogicException("Vous ne pouvez pas postuler pour votre annonce.");
        }else{
            //Test si l'utilisateur à déja postulé pour cette annonce
            $res = false;        

            foreach($annonce->getPostules() as $postule){   //à modifier
                if($user->getId() === $postule->getUser()->getId()){
                    $res = true;
                }
            }

            if($res){   //l'utilisateur a déjà postulé
                throw new LogicException("Vous avez deja postulé pour cette annonce.");
            }else{
                //création de postuler
                $postuler = new Postuler();
                //modification deS l'attribut de postuler
                $postuler->setEtat(false);
                $postuler->setDateCreate(new \DateTime());
                
                //ajout de l'utilisateur a postuler
                $postuler->setUser($user);
                //ajout de postuler à l'user
                $user->addPostule($postuler);
                //ajout de postuler à l'annonce
                $annonce->addPostule($postuler);
                //ajout de l'annonce à postuler
                $postuler->setAnnonce($annonce);            
                //Récupère le manager
                $em = $this->getDoctrine()->getManager();
                //doctrine se charge de postuler et de user
                $em->persist($postuler);
                $em->persist($user);
                //modification dans la bd
                $em->flush();
                //ajout d'un message flash
                $this->addFlash('notice', "Vous avez postulé pour l'annonce.");
                //redirection vers l'annonce
                return $this->forward('TecServiceBundle:Service:getAnnonce', array('id' => $id));
            }
        }
    }
    
    /**
     * 
     * @param type $id
     * id est l'id de postuler
     * L'utilisateur qui a posté une annonce accepte 
     * Création d'un service
     */
    public function acceptePostuleUserAction($id){
        //On vérifie que l'utilisateur est connecté
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
          // Sinon on déclenche une exception « Accès interdit »
          throw new AccessDeniedException('Accès limité.');
        }
        //Récupère le repository postuler
        $repository = $this->getDoctrine()->getManager()->getRepository('TecServiceBundle:Postuler');
        //Récupère postuler qui possède l'id $id
        $postuler = $repository->find($id);
        //Test si postuler existe
        if($postuler === null){
            throw new NotFoundHttpException("La demande n'existe pas.");
        }
        //Si postuler existe
        
        //Récupère l'utilisateur en session
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        //test si l'utilisateur en session est le proprietaire de l'annonce
        if($user->getId() != $postuler->getAnnonce()->getUser->getId()){
            throw new AccessDeniedException("Vous n'etes pas l'auteur de l'annonce.");
        }
        
        //Change l'etat de postuler à true
        $postuler->setEtat(true);
        $postuler->setDateUpdate(new \DateTime());
        //Création du service
        $service = new Service();
        //Mise a jour des attributs de service
        $service->setActive(true);
        $service->setDateService(new \DateTime());  //date du service à voir ou est-ce qu'on va la chercher
        //Création d'une notification
        //...
        
        //
        
        //Récupère le manager
        $em = $this->getDoctrine()->getManager();
        //Doctrine se charge de service
        $em->persist($service);
        
        //Création de demander
        $demander = new Demander();
        $demander->setUser($user);
        $demander->setService($service);
        //Création de fournir
        $fournir = new Fournir();
        $fournir->setFournisseur($user);
        $fournir->setService($service);
        
        //doctrine se charge de demander et fournir
        $em->persist($demander);
        $em->persist($fournir);
        //Sauvegarde en bd
        $em->flush();        
        
        //Redirection vers la page de profil
        return $this->forward('TecServiceBundle:Service:results');
    }
    
    /**
     * 
     * @param type $id
     * id est l'id de postuler
     * l'utilisateur qui a posté une annonce refuse
     */
    public function refusePostuleUserAction($id){
        //On vérifie que l'utilisateur est connecté
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
          // Sinon on déclenche une exception « Accès interdit »
          throw new AccessDeniedException('Accès limité.');
        }
        //Récupère le repository postuler
        $repository = $this->getDoctrine()->getManager()->getRepository('TecServiceBundle:Postuler');
        //Récupère postuler qui possède l'id $id
        $postuler = $repository->find($id);
        //Test si postuler existe
        if($postuler === null){
            throw new NotFoundHttpException("La demande n'existe pas.");
        }
        //Si postuler existe
        
        //Récupère l'utilisateur en session
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        //test si l'utilisateur en session est le proprietaire de l'annonce
        if($user->getId() != $postuler->getAnnonce()->getUser->getId()){
            throw new AccessDeniedException("Vous n'etes pas l'auteur de l'annonce.");
        }
        
        //Change l'etat de postuler à true
        $postuler->setEtat(false);        
        $postuler->setDateUpdate(new \DateTime());
        //Création d'une notification
        //...
        
        //
        
        //Récupère le manager
        $em = $this->getDoctrine()->getManager();
        //Sauvegarde en bd
        $em->flush();
        //Redirection vers la page de profil
        return $this->forward('TecServiceBundle:Service:results');
    }
    
    /**
     * 
     * @param type $chaine
     * @return type
     * 
     * Recherche d'une annonce par titre
     * 
     * la chaine peut contenir plusieurs mots
     * mais le titre doit etre identique au mot
     */
    public function searchAnnonceAction($chaine){   //Recherche par titre
        
        $sql = "SELECT id, title FROM annonce ";
        
        $tab = explode(" ", $chaine);
        
        $string = "WHERE title = ? ";
        $indice = 0;
        while($indice<count($tab)){   
            $sql = $sql.$string;                
            $string = "OR title = ? ";
            $indice ++;
        }
        
        $rsm = new ResultSetMapping;
        $rsm->addEntityResult('TecServiceBundle:Annonce', 'a');
        $rsm->addFieldResult('a', 'id', 'id');
        $rsm->addFieldResult('a', 'title', 'title');

        $query = $this->getDoctrine()->getEntityManager()->createNativeQuery($sql, $rsm);
        
        $indice = 0;
        
        while($indice<count($tab)){
            $query->setParameter(($indice+1), $tab[$indice]);
            $indice++;
        }

        $annonces = $query->getResult();
        
        return $this->render('TecServiceBundle::searchAnnonce.html.twig', array('annonces' => $annonces));
    }
    
    /**
     * 
     * @param Request $request
     * 
     * Recherche une annonce par categorie et/ou par localite
     */
    public function searchAnnonce2Action(Request $request){
        //Récupère les valeurs du formulaire
        $localite = $request->get('localite');
        $idsubcategorie = $request->get('categorie');
        
        $id = intval($idsubcategorie);
       
        if((strlen($localite) === 0)&&($id === 0)){   //Aucun choix pour la recherche
            return $this->forward('TecServiceBundle:Service:getAllAnnonce');
        }else if((strlen($localite) > 0) && ($id > 0)){ //Recherche par sous-categorie et par localite
            //Traitement            
            //Récupère le manager
            $em = $this->getDoctrine()->getManager();
            //Récupère le repository subcategorie
            $repository = $em->getRepository('TecServiceBundle:Sub_categorie');
            //Récupère la sous categorie
            $subcategorie = $repository->find($id);
            //création de la requete
            $query = $em->createQuery(
                    'SELECT a 
                     FROM TecServiceBundle:Sub_Categorie sc, TecServiceBundle:Annonce a, TecUserBundle:User u, TecUserBundle:Addresse ad
                     WHERE sc.name like :subcategorie AND a.user = u.id AND u.id = ad.user and ad.city like :localite
                     ORDER BY a.title ASC')
                    
                    ->setParameter('subcategorie', $subcategorie->getName())
                    ->setParameter('localite', $localite);
                                
            $annonces = $query->getResult();
            return $this->render('TecServiceBundle::getAllAnnonce.html.twig', array('annonces' => $annonces));
        }else if(strlen($localite) > 0){   //Recherche seulement par localite
            //Traitement
            //Récupère le manager
            $em = $this->getDoctrine()->getManager();
            //création de la requete
            $query = $em->createQuery(
                    'SELECT a 
                     FROM TecServiceBundle:Annonce a, TecUserBundle:User u, TecUserBundle:Addresse ad
                     WHERE a.user = u.id AND u.id = ad.user and ad.city like :localite
                     ORDER BY a.title ASC')

                    ->setParameter('localite', $localite);
                                
            $annonces = $query->getResult();
            return $this->render('TecServiceBundle::getAllAnnonce.html.twig', array('annonces' => $annonces));
        }else{  //Recherche seulement par categorie
            return $this->forward('TecServiceBundle:Service:getAnnonceCategorie', array('id' => $id));
        }        
        
        return $this->render('TecServiceBundle::results.html.twig');
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
                        
            //Récupère l'extension de l'image
            $extension = $categorie->getMedia()->getFile()->guessExtension();
            //Test si l'extension est = à jpg, jpeg ou png
            if(($extension != "jpg")&&($extension!="png")&&($extension!="jpeg")){
                //Ajout d'un message flash en session
                $this->addFlash('notice', "Erreur extension jpg, jpeg ou png.");
            }else{            
                //Récupère le manager
                $em = $this->getDoctrine()->getManager();                 
                //recupérer l'image et modifier son nom temporaire, nom final (id + categorie)
                $categorie->getMedia()->setTempFilename("categorie");             
                //Doctrine se charge de l'entity categorie
                $em->persist($categorie);
                //Sauvegarde en bd
                $em->flush();
                
                /**
                 * sauvegarde automatique de l'image
                 */
                
                //Ajout d'un message flash
                $this->addFlash('notice', "Ajout categorie OK.");
            }
            
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
    
    /**
     * 
     * @param type $id
     * 
     * Retourne les annonces de la categorie qui possède l'id passé en paramètre
     */
    public function getAnnonceCategorieAction($id){
        //Récupère le repository de categorie
        $repository = $this->getDoctrine()->getManager()->getRepository('TecServiceBundle:Categorie');
        //Récupère la categorie qui possède l'id $id
        $categorie = $repository->find($id);
        //Test si la categorie existe
        if($categorie === null){
            throw new NotFoundHttpException("La categorie n'existe pas");  //genere une exception
        }
        $annonces = new \Doctrine\Common\Collections\ArrayCollection();
        
        foreach($categorie->getSubCategories() as $subcategorie){
            foreach($subcategorie->getAnnonces() as $annonce){
                $annonces->add($annonce);
            }
        }                
        //Renvoie vers la page qui affiche les annonces de la categories
        return $this->render('TecServiceBundle::getAllAnnonce.html.twig', array('annonces' => $annonces));
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
     * @param type $id
     * 
     * Retourne les annonces de la categorie qui possède l'id passé en paramètre
     */
    public function getAnnonceSubCategorieAction($id){
        //Récupère le repository de sub_categorie
        $repository = $this->getDoctrine()->getManager()->getRepository('TecServiceBundle:Sub_categorie');
        //Récupère la sous categorie qui possède l'id $id
        $subcategorie = $repository->find($id);
        //Test si la sous categorie existe
        if($subcategorie === null){
            throw new NotFoundHttpException("La sous categorie n'existe pas");  //genere une exception
        }
        
        $annonces = new \Doctrine\Common\Collections\ArrayCollection();
        
        foreach($subcategorie->getAnnonces() as $annonce){
            $annonces->add($annonce);
        }
                
        //Renvoie vers la page qui affiche les annonces de la categories
        return $this->render('TecServiceBundle::getAllAnnonce.html.twig', array('annonces' => $annonces));
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
        //Récupère le repository de type
        $repository = $this->getDoctrine()->getManager()->getRepository('TecServiceBundle:Type');
        //Récupère le type qui possède l'id $id
        $type = $repository->find($id);
        //Test si le type existe
        if($type === null){
            throw new NotFoundHttpException("Le type n'existe pas");  //genere une exception
        }
        //Renvoie vers la page qui affiche le type
        return $this->render('TecServiceBundle::getType.html.twig', array('type' => $type));
    }
    
    /**
     * 
     * @param type $id
     * id = l'id du type offre ou demande
     */
    public function getAnnonceType($id){
        //Récupère le repository de type
        $repository = $this->getDoctrine()->getManager()->getRepository('TecServiceBundle:Type');
        //Récupère le type qui possède l'id $id
        $type = $repository->find($id);
        //Test si le type existe
        if($type === null){
            throw new NotFoundHttpException("Le type n'existe pas");  //genere une exception
        }
        //Renvoie vers la page qui affiche les annonces
        return $this->render('TecServiceBundle::getAllAnnonce.html.twig', array('annonces' => $type->getAnnonces()));
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
        
        //On vérifie que l'utilisateur est un admin
        if ((!$this->get('security.context')->isGranted('ROLE_ADMIN'))||($usersess->getId() != $user->getId() )) {
          // Sinon on déclenche une exception « Accès interdit »
          throw new AccessDeniedException('Accès limité.');
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
