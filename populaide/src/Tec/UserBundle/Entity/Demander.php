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


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}

