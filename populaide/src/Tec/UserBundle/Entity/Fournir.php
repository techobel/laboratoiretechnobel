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
     * @var integer
     *
     * @ORM\Column(name="note", type="integer", nullable=true)
     */
    private $note;
    
    /********************************************************
     *                      RELATION TABLES                 *
     ********************************************************/
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="fournit")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;
    
    /**
     * @ORM\ManyToOne(targetEntity="Tec\ServiceBundle\Entity\Service", inversedBy="fournisseurs")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id", onDelete="CASCADE")
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
     * Set user
     *
     * @param \Tec\UserBundle\Entity\User $user
     *
     * @return Fournir
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
