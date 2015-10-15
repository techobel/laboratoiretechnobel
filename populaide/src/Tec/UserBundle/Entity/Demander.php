<?php

namespace Tec\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Demander
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Tec\UserBundle\Entity\DemanderRepository")
 */
class Demander
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /********************************************************
     *                      RELATION TABLES                 *
     ********************************************************/
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="demandes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;
    
    /**
     * @ORM\OneToOne(targetEntity="Tec\ServiceBundle\Entity\Service", inversedBy="demande", cascade={"persist"})
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
     * Set user
     *
     * @param \Tec\UserBundle\Entity\User $user
     *
     * @return Demander
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
     * @return Demander
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
