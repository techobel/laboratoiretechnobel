<?php

namespace Tec\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Tec\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    
    /********************************************************
     *                      ATTRIBUTS                       *
     ********************************************************/
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="name", type="string", length=50, nullable=true)
     */
    private $name;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="first_name", type="string", length=50, nullable=true)
     */
    private $first_name;
    
    /**
     * @var date
     * 
     * @ORM\Column(name="birth_date", type="date", nullable=true)
     */
    private $birth_date;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="sex", type="string", length=10, nullable=true)
     */
    private $sex;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="phone", type="string", length=50, nullable=true)
     */
    private $phone;
    
    /**
     * @var date
     * 
     * @ORM\Column(name="creation_date", type="date", nullable=false)
     */
    private $creation_date;
    
    /**
     * @var update_date
     * 
     * @ORM\Column(name="update_date", type="date", nullable=true)
     */
    private $update_date;
    
    /**
     * @var disponible
     * 
     * @ORM\Column(name="disponible", type="boolean", nullable=false)
     */
    private $disponible;
    
    /**
     * CONSTRUCTEUR
     */
    
    public function __construct(){
        parent::__construct();
        $this->setCreationDate(new \DateTime());
        $this->setDisponible(true);
    }
    
    
    /********************************************************
     *                      RELATION TABLES                 *
     ********************************************************/
    
    /**
     * @ORM\OneToOne(targetEntity="Adresse", mappedBy="user", cascade={"persist", "remove"})
     */
    private $adresse;
    
    /**
     * @ORM\OneToOne(targetEntity="Tec\ServiceBundle\Entity\Media", inversedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $media;
    
    /**
     * l'utilisateur demande un service
     * @ORM\OneToMany(targetEntity="Demander", mappedBy="user")
     */
    private $demandes;
    
    /**
     * l'utilisateur fournit un service
     * @ORM\OneToMany(targetEntity="Fournir", mappedBy="user")
     */
    private $fournit;
    
    /**
     * l'utilisateur postule à une annonce
     * @ORM\OneToMany(targetEntity="Tec\ServiceBundle\Entity\Postuler", mappedBy="user", cascade={"persist", "remove"})
     */
    private $postules;
    
    /**
     * Annonce de l'utilisateur
     * @ORM\OneToMany(targetEntity="Tec\ServiceBundle\Entity\Annonce", mappedBy="user", cascade={"persist", "remove"})
     */
    private $annonces;
    
    /**
     * @ORM\OneToMany(targetEntity="Notification", mappedBy="user", cascade={"persist", "remove"})
     */
    private $notifications;
    
    /********************************************************
     *                      GETTER/SETTER                   *
     ********************************************************/
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     *
     * @return User
     */
    public function setBirthDate($birthDate)
    {
        $this->birth_date = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birth_date;
    }

    /**
     * Set sex
     *
     * @param string $sex
     *
     * @return User
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return User
     */
    public function setCreationDate($creationDate)
    {
        $this->creation_date = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creation_date;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     *
     * @return User
     */
    public function setUpdateDate($updateDate)
    {
        $this->update_date = $updateDate;

        return $this;
    }

    /**
     * Get updateDate
     *
     * @return \DateTime
     */
    public function getUpdateDate()
    {
        return $this->update_date;
    }

    /**
     * Get media
     *
     * @return \Tec\ServiceBundle\Media
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Add demande
     *
     * @param \Tec\UserBundle\Entity\Demander $demande
     *
     * @return User
     */
    public function addDemande(\Tec\UserBundle\Entity\Demander $demande)
    {
        $this->demandes[] = $demande;

        return $this;
    }

    /**
     * Remove demande
     *
     * @param \Tec\UserBundle\Entity\Demander $demande
     */
    public function removeDemande(\Tec\UserBundle\Entity\Demander $demande)
    {
        $this->demandes->removeElement($demande);
    }

    /**
     * Get demandes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDemandes()
    {
        return $this->demandes;
    }

    /**
     * Set disponible
     *
     * @param boolean $disponible
     *
     * @return User
     */
    public function setDisponible($disponible)
    {
        $this->disponible = $disponible;

        return $this;
    }

    /**
     * Get disponible
     *
     * @return boolean
     */
    public function getDisponible()
    {
        return $this->disponible;
    }

    /**
     * Add annonce
     *
     * @param \Tec\ServiceBundle\Entity\Annonce $annonce
     *
     * @return User
     */
    public function addAnnonce(\Tec\ServiceBundle\Entity\Annonce $annonce)
    {
        $this->annonces[] = $annonce;

        return $this;
    }

    /**
     * Remove annonce
     *
     * @param \Tec\ServiceBundle\Entity\Annonce $annonce
     */
    public function removeAnnonce(\Tec\ServiceBundle\Entity\Annonce $annonce)
    {
        $this->annonces->removeElement($annonce);
    }

    /**
     * Get annonces
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnnonces()
    {
        return $this->annonces;
    }

    /**
     * Set media
     *
     * @param \Tec\ServiceBundle\Entity\Media $media
     *
     * @return User
     */
    public function setMedia(\Tec\ServiceBundle\Entity\Media $media = null)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Add postule
     *
     * @param \Tec\ServiceBundle\Entity\Postuler $postule
     *
     * @return User
     */
    public function addPostule(\Tec\ServiceBundle\Entity\Postuler $postule)
    {
        $this->postules[] = $postule;

        return $this;
    }

    /**
     * Remove postule
     *
     * @param \Tec\ServiceBundle\Entity\Postuler $postule
     */
    public function removePostule(\Tec\ServiceBundle\Entity\Postuler $postule)
    {
        $this->postules->removeElement($postule);
    }

    /**
     * Get postules
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPostules()
    {
        return $this->postules;
    }


    /**
     * Add notification
     *
     * @param \Tec\UserBundle\Entity\Notification $notification
     *
     * @return User
     */
    public function addNotification(\Tec\UserBundle\Entity\Notification $notification)
    {
        $this->notification[] = $notification;

        return $this;
    }

    /**
     * Remove notification
     *
     * @param \Tec\UserBundle\Entity\Notification $notification
     */
    public function removeNotification(\Tec\UserBundle\Entity\Notification $notification)
    {
        $this->notification->removeElement($notification);
    }

    /**
     * Get notification
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * Add fournit
     *
     * @param \Tec\UserBundle\Entity\Fournir $fournit
     *
     * @return User
     */
    public function addFournit(\Tec\UserBundle\Entity\Fournir $fournit)
    {
        $this->fournit[] = $fournit;

        return $this;
    }

    /**
     * Remove fournit
     *
     * @param \Tec\UserBundle\Entity\Fournir $fournit
     */
    public function removeFournit(\Tec\UserBundle\Entity\Fournir $fournit)
    {
        $this->fournit->removeElement($fournit);
    }

    /**
     * Get fournit
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFournit()
    {
        return $this->fournit;
    }

    /**
     * Get notifications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Add adresse
     *
     * @param \Tec\UserBundle\Entity\Adresse $adresse
     *
     * @return User
     */
    public function addAdresse(\Tec\UserBundle\Entity\Adresse $adresse)
    {
        $this->adresse[] = $adresse;

        return $this;
    }

    /**
     * Remove adresse
     *
     * @param \Tec\UserBundle\Entity\Adresse $adresse
     */
    public function removeAdresse(\Tec\UserBundle\Entity\Adresse $adresse)
    {
        $this->adresse->removeElement($adresse);
    }

    /**
     * Get adresse
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdresse()
    {
        return $this->adresse;
    }
}
