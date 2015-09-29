<?php

namespace Tec\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Service
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Tec\ServiceBundle\Entity\ServiceRepository")
 */
class Service
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
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;
    
    /**
     * @var date
     * 
     * @ORM\Column(name="date_service", type="date")
     */
    private $date_service;
    
    /********************************************************
     *                      RELATION TABLES                 *
     ********************************************************/

    /**
     * @ORM\OneToOne(targetEntity="Tec\UserBundle\Entity\Demander", mappedBy="service")
     */
    private $demande;
    
    /**
     * @ORM\OneToMany(targetEntity="Tec\UserBundle\Entity\User", mappedBy="service")
     */
    private $fournisseurs;
    
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
     * Set active
     *
     * @param boolean $active
     *
     * @return Service
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
     * Set dateService
     *
     * @param \DateTime $dateService
     *
     * @return Service
     */
    public function setDateService($dateService)
    {
        $this->date_service = $dateService;

        return $this;
    }

    /**
     * Get dateService
     *
     * @return \DateTime
     */
    public function getDateService()
    {
        return $this->date_service;
    }

    /**
     * Set demande
     *
     * @param \Tec\ServiceBundle\Entity\Demander $demande
     *
     * @return Service
     */
    public function setDemande(\Tec\ServiceBundle\Entity\Demander $demande = null)
    {
        $this->demande = $demande;

        return $this;
    }

    /**
     * Get demande
     *
     * @return \Tec\ServiceBundle\Entity\Demander
     */
    public function getDemande()
    {
        return $this->demande;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fournisseurs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add fournisseur
     *
     * @param \Tec\UserBundle\Entity\User $fournisseur
     *
     * @return Service
     */
    public function addFournisseur(\Tec\UserBundle\Entity\User $fournisseur)
    {
        $this->fournisseurs[] = $fournisseur;

        return $this;
    }

    /**
     * Remove fournisseur
     *
     * @param \Tec\UserBundle\Entity\User $fournisseur
     */
    public function removeFournisseur(\Tec\UserBundle\Entity\User $fournisseur)
    {
        $this->fournisseurs->removeElement($fournisseur);
    }

    /**
     * Get fournisseurs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFournisseurs()
    {
        return $this->fournisseurs;
    }
}
