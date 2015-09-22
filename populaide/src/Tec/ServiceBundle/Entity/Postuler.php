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
}

