<?php

namespace Tec\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Annonce
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Tec\ServiceBundle\Entity\AnnonceRepository")
 */
class Annonce
{
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
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="remarques", type="string", length=150)
     */
    private $remarques;

    /**
     * @var integer
     *
     * @ORM\Column(name="perimetre", type="integer")
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
     * @ORM\Column(name="update_date", type="date")
     */
    private $updateDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="delete_date", type="date")
     */
    private $deleteDate;

    
    /**
     * @ORM\ManyToOne(targetEntity="Type", inversedBy="annonces")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    private $type;
    
    /**
     * @ORM\ManyToOne(targetEntity="Sub_categorie", inversedBy="annonces")
     * @ORM\JoinColumn(name="sub_categorie_id", referencedColumnName="id")
     */
    private $sub_categorie;

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
}
