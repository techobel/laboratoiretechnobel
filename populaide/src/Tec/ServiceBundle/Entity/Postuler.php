<?php

namespace Tec\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Postuler
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Tec\ServiceBundle\Entity\PostulerRepository")
 */
class Postuler
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
     * @var boolean
     *
     * @ORM\Column(name="etat", type="boolean")
     */
    private $etat;
    
    /**
     * @ORM\ManyToMany(targetEntity="Tec\UserBundle\Entity\User", mappedBy="postules")
     */
    private $users;
    
    /**
     * @ORM\ManyToOne(targetEntity="Annonce", inversedBy="postules")
     * @ORM\JoinColumn(name="annonce_id", referencedColumnName="id")
     */
    private $annonce;


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
     * Set etat
     *
     * @param boolean $etat
     *
     * @return Postuler
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return boolean
     */
    public function getEtat()
    {
        return $this->etat;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add user
     *
     * @param \Tec\UserBundle\Entity\User $user
     *
     * @return Postuler
     */
    public function addUser(\Tec\UserBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Tec\UserBundle\Entity\User $user
     */
    public function removeUser(\Tec\UserBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set annonce
     *
     * @param \Tec\ServiceBundle\Entity\Annonce $annonce
     *
     * @return Postuler
     */
    public function setAnnonce(\Tec\ServiceBundle\Entity\Annonce $annonce = null)
    {
        $this->annonce = $annonce;

        return $this;
    }

    /**
     * Get annonce
     *
     * @return \Tec\ServiceBundle\Entity\Annonce
     */
    public function getAnnonce()
    {
        return $this->annonce;
    }
}
