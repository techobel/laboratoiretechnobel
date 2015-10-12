<?php

namespace Tec\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Categorie
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Tec\ServiceBundle\Entity\CategorieRepository")
 */
class Categorie
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
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=150)
     */
    private $description;
    
    /********************************************************
     *                      RELATION TABLES                 *
     ********************************************************/
    
    /**
     * @ORM\OneToOne(targetEntity="Media", inversedBy="categorie", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=false)
     */
    private $media;
    
    /**
     * @ORM\OneToMany(targetEntity="Sub_categorie", mappedBy="categorie", cascade={"persist"})
     */
    private $sub_categories;

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
     * @return Categorie
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
     * Set description
     *
     * @param string $description
     *
     * @return Categorie
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
     * Set media
     *
     * @param \Tec\ServiceBundle\Entity\Media $media
     *
     * @return Categorie
     */
    public function setMedia(\Tec\ServiceBundle\Entity\Media $media = null)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return \Tec\ServiceBundle\Entity\Media
     */
    public function getMedia()
    {
        return $this->media;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sub_categories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add subCategory
     *
     * @param \Tec\ServiceBundle\Entity\Sub_categorie $subCategory
     *
     * @return Categorie
     */
    public function addSubCategory(\Tec\ServiceBundle\Entity\Sub_categorie $subCategory)
    {
        $this->sub_categories[] = $subCategory;

        return $this;
    }

    /**
     * Remove subCategory
     *
     * @param \Tec\ServiceBundle\Entity\Sub_categorie $subCategory
     */
    public function removeSubCategory(\Tec\ServiceBundle\Entity\Sub_categorie $subCategory)
    {
        $this->sub_categories->removeElement($subCategory);
    }

    /**
     * Get subCategories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubCategories()
    {
        return $this->sub_categories;
    }
}
