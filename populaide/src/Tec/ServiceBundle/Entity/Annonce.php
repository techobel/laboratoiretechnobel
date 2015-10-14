<?php

namespace Tec\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Annonce
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Tec\ServiceBundle\Entity\AnnonceRepository")
 */
class Annonce
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
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=70)
     * @Assert\Length(
     *      min = 3,
     *      max = 70)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     * @Assert\Length(
     *      min = 10,
     *      max = 255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="remarques", type="string", length=150, nullable=true)
     * @Assert\Length(
     *      min = 3,
     *      max = 150)
     */
    private $remarques;

    /**
     * @var integer
     *
     * @ORM\Column(name="perimetre", type="integer")
     * @Assert\Range(
     *      min = 0)
     */
    private $perimetre;

    /**
     * @var boolean
     *
     * @ORM\Column(name="aide", type="boolean")
     */
    private $aide;

    /**
     * @var boolean
     *
     * @ORM\Column(name="diffusion", type="boolean")
     */
    private $diffusion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="date")
     */
    private $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_date", type="date", nullable=true)
     */
    private $updateDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="delete_date", type="date", nullable=true)
     */
    private $deleteDate;

    
    /********************************************************
     *                      RELATION TABLES                 *
     ********************************************************/
    
    /**
     * @ORM\ManyToOne(targetEntity="Type", inversedBy="annonces")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id", nullable=false)
     */
    private $type;
    
    /**
     * @ORM\ManyToOne(targetEntity="Sub_categorie", inversedBy="annonces")
     * @ORM\JoinColumn(name="sub_categorie_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $sub_categorie;
    
    /**
     * @ORM\OneToMany(targetEntity="Postuler", mappedBy="annonce", cascade={"persist", "remove"})
     */
    private $postules;
    
    //l'utilisateur qui a posté l'annonce
    /**
     * @ORM\ManyToOne(targetEntity="Tec\UserBundle\Entity\User", inversedBy="annonces", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $user;
    
    /**
     * @ORM\OneToOne(targetEntity="Service", mappedBy="annonce")
     */
    private $service;
    
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
     * Set title
     *
     * @param string $title
     *
     * @return Annonce
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Annonce
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set remarques
     *
     * @param string $remarques
     *
     * @return Annonce
     */
    public function setRemarques($remarques)
    {
        $this->remarques = $remarques;

        return $this;
    }

    /**
     * Get remarques
     *
     * @return string
     */
    public function getRemarques()
    {
        return $this->remarques;
    }

    /**
     * Set perimetre
     *
     * @param integer $perimetre
     *
     * @return Annonce
     */
    public function setPerimetre($perimetre)
    {
        $this->perimetre = $perimetre;

        return $this;
    }

    /**
     * Get perimetre
     *
     * @return integer
     */
    public function getPerimetre()
    {
        return $this->perimetre;
    }

    /**
     * Set aide
     *
     * @param boolean $aide
     *
     * @return Annonce
     */
    public function setAide($aide)
    {
        $this->aide = $aide;

        return $this;
    }

    /**
     * Get aide
     *
     * @return boolean
     */
    public function getAide()
    {
        return $this->aide;
    }

    /**
     * Set diffusion
     *
     * @param boolean $diffusion
     *
     * @return Annonce
     */
    public function setDiffusion($diffusion)
    {
        $this->diffusion = $diffusion;

        return $this;
    }

    /**
     * Get diffusion
     *
     * @return boolean
     */
    public function getDiffusion()
    {
        return $this->diffusion;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Annonce
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return Annonce
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     *
     * @return Annonce
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get updateDate
     *
     * @return \DateTime
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * Set deleteDate
     *
     * @param \DateTime $deleteDate
     *
     * @return Annonce
     */
    public function setDeleteDate($deleteDate)
    {
        $this->deleteDate = $deleteDate;

        return $this;
    }

    /**
     * Get deleteDate
     *
     * @return \DateTime
     */
    public function getDeleteDate()
    {
        return $this->deleteDate;
    }

    /**
     * Set type
     *
     * @param \Tec\ServiceBundle\Entity\Type $type
     *
     * @return Annonce
     */
    public function setType(\Tec\ServiceBundle\Entity\Type $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Tec\ServiceBundle\Entity\Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set subCategorie
     *
     * @param \Tec\ServiceBundle\Entity\Sub_categorie $subCategorie
     *
     * @return Annonce
     */
    public function setSubCategorie(\Tec\ServiceBundle\Entity\Sub_categorie $subCategorie = null)
    {
        $this->sub_categorie = $subCategorie;

        return $this;
    }

    /**
     * Get subCategorie
     *
     * @return \Tec\ServiceBundle\Entity\Sub_categorie
     */
    public function getSubCategorie()
    {
        return $this->sub_categorie;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->postules = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add postule
     *
     * @param \Tec\ServiceBundle\Entity\Postuler $postule
     *
     * @return Annonce
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
     * Set user
     *
     * @param \Tec\UserBundle\Entity\User $user
     *
     * @return Annonce
     */
    public function setUser(\Tec\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Tec\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set service
     *
     * @param \Tec\ServiceBundle\Entity\Service $service
     *
     * @return Annonce
     */
    public function setService(\Tec\ServiceBundle\Entity\Service $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \Tec\ServiceBundle\Entity\Service
     */
    public function getService()
    {
        return $this->service;
    }
}
