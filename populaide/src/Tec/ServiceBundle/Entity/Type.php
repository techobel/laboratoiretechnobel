<?php

namespace Tec\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Type
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Tec\ServiceBundle\Entity\TypeRepository")
 */
class Type
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
     * @ORM\Column(name="intitule", type="string", length=20)
     */
    private $intitule;
    
    /********************************************************
     *                      RELATION TABLES                 *
     ********************************************************/
    
    /**
     * @ORM\OneToMany(targetEntity="Annonce", mappedBy="annonce")
     */
    private $annonces;

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
     * Set intitule
     *
     * @param string $intitule
     *
     * @return Type
     */
    public function setIntitule($intitule)
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * Get intitule
     *
     * @return string
     */
    public function getIntitule()
    {
        return $this->intitule;
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
     * @return Type
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
