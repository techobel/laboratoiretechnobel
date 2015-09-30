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
     * @ORM\Column(name="creation_date", type="date", nullable=true)
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
     * @ORM\Column(name="disponible", type="boolean", nullable=true)
     */
    private $disponible;
    
    
    /********************************************************
     *                      RELATION TABLES                 *
     ********************************************************/
    
    /**
     * @ORM\OneToMany(targetEntity="Addresse", mappedBy="User", cascade={"persist"})
     */
    private $addresses;
    
    /**
     * @ORM\OneToOne(targetEntity="Tec\ServiceBundle\Entity\Media", inversedBy="user", cascade={"persist"})
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id")
     */
    private $media;
    
    /**
     * l'utilisateur demande un service
     * @ORM\OneToMany(targetEntity="Demander", mappedBy="User")
     */
    private $demandes;
    
    /**
     * l'utilisateur fournit un service
     * @ORM\ManyToMany(targetEntity="Fournir", inversedBy="fournisseur")
     * @ORM\JoinTable(name="users_fournisseur_service")
     */
    private $fournisseur;
    
    /**
     * l'utilisateur postule à une annonce
     * @ORM\ManyToMany(targetEntity="Tec\ServiceBundle\Entity\Postuler", inversedBy="users")
     * @ORM\JoinTable(name="users_postule")
     */
    private $postules;

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
     * Add address
     *
     * @param \Tec\UserBundle\Entity\Addresse $address
     *
     * @return User
     */
    public function addAddress(\Tec\UserBundle\Entity\Addresse $address)
    {
        $this->addresses[] = $address;

        return $this;
    }

    /**
     * Remove address
     *
     * @param \Tec\UserBundle\Entity\Addresse $address
     */
    public function removeAddress(\Tec\UserBundle\Entity\Addresse $address)
    {
        $this->addresses->removeElement($address);
    }

    /**
     * Get addresses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * Set media
     *
     * @param \Tec\ServiceBundle\Media $media
     *
     * @return User
     */
    public function setMedia(\Tec\ServiceBundle\Media $media = null)
    {
        $this->media = $media;

        return $this;
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
     * Add serviceFournisseur
     *
     * @param \Tec\UserBundle\Entity\Fournir $serviceFournisseur
     *
     * @return User
     */
    public function addServiceFournisseur(\Tec\UserBundle\Entity\Fournir $serviceFournisseur)
    {
        $this->service_fournisseur[] = $serviceFournisseur;

        return $this;
    }

    /**
     * Remove serviceFournisseur
     *
     * @param \Tec\UserBundle\Entity\Fournir $serviceFournisseur
     */
    public function removeServiceFournisseur(\Tec\UserBundle\Entity\Fournir $serviceFournisseur)
    {
        $this->service_fournisseur->removeElement($serviceFournisseur);
    }

    /**
     * Get serviceFournisseur
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiceFournisseur()
    {
        return $this->service_fournisseur;
    }

    /**
     * Add fournisseur
     *
     * @param \Tec\UserBundle\Entity\Fournir $fournisseur
     *
     * @return User
     */
    public function addFournisseur(\Tec\UserBundle\Entity\Fournir $fournisseur)
    {
        $this->fournisseur[] = $fournisseur;

        return $this;
    }

    /**
     * Remove fournisseur
     *
     * @param \Tec\UserBundle\Entity\Fournir $fournisseur
     */
    public function removeFournisseur(\Tec\UserBundle\Entity\Fournir $fournisseur)
    {
        $this->fournisseur->removeElement($fournisseur);
    }

    /**
     * Get fournisseur
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFournisseur()
    {
        return $this->fournisseur;
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
}
