<?php

namespace Tec\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fournir
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Tec\UserBundle\Entity\FournirRepository")
 */
class Fournir
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
     * @var integer
     *
     * @ORM\Column(name="note", type="integer")
     */
    private $note;
    
    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="fournisseur")
     */
    private $fournisseur;
    
    /**
     * @ORM\ManyToOne(targetEntity="Tec\ServiceBundle\Entity\Service", inversedBy="fournisseur_service")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id")
     */
    private $service;
    


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
     * Set note
     *
     * @param integer $note
     *
     * @return Fournir
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return integer
     */
    public function getNote()
    {
        return $this->note;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fournisseur_service = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add fournisseurService
     *
     * @param \Tec\UserBundle\Entity\User $fournisseurService
     *
     * @return Fournir
     */
    public function addFournisseurService(\Tec\UserBundle\Entity\User $fournisseurService)
    {
        $this->fournisseur_service[] = $fournisseurService;

        return $this;
    }

    /**
     * Remove fournisseurService
     *
     * @param \Tec\UserBundle\Entity\User $fournisseurService
     */
    public function removeFournisseurService(\Tec\UserBundle\Entity\User $fournisseurService)
    {
        $this->fournisseur_service->removeElement($fournisseurService);
    }

    /**
     * Get fournisseurService
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFournisseurService()
    {
        return $this->fournisseur_service;
    }

    /**
     * Add fournisseur
     *
     * @param \Tec\UserBundle\Entity\User $fournisseur
     *
     * @return Fournir
     */
    public function addFournisseur(\Tec\UserBundle\Entity\User $fournisseur)
    {
        $this->fournisseur[] = $fournisseur;

        return $this;
    }

    /**
     * Remove fournisseur
     *
     * @param \Tec\UserBundle\Entity\User $fournisseur
     */
    public function removeFournisseur(\Tec\UserBundle\Entity\User $fournisseur)
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
     * Set service
     *
     * @param \Tec\ServiceBundle\Entity\Service $service
     *
     * @return Fournir
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
