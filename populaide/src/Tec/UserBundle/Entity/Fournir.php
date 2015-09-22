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
}

