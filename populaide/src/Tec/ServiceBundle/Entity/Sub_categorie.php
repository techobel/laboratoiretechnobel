<?php

namespace Tec\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sub_categorie
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Tec\ServiceBundle\Entity\Sub_categorieRepository")
 */
class Sub_categorie
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
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=150)
     */
    private $description;
    
    /**
     * @ORM\ManyToOne(targetEntity="Categorie", inversedBy="sub_categories")
     * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id")
     */
    private $categorie;
    
    /**
     * @ORM\OneToMany(targetEntity="Annonce", mappedBy="sub_categorie")
     */
    private $annonces;

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
     * @return Sub_categorie
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
     * @return Sub_categorie
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
     * Set categorie
     *
     * @param \Tec\ServiceBundle\Entity\Categorie $categorie
     *
     * @return Sub_categorie
     */
    public function setCategorie(\Tec\ServiceBundle\Entity\Categorie $categorie = null)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \Tec\ServiceBundle\Entity\Categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->annonces = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add annonce
     *
     * @param \Tec\ServiceBundle\Entity\Annonce $annonce
     *
     * @return Sub_categorie
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
}
