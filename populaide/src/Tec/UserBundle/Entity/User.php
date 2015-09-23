<?php

namespace Tec\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Tec\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="first_name", type="string", length=50)
     */
    private $first_name;
    
    /**
     * @var date
     * 
     * @ORM\Column(name="birth_date", type="date")
     */
    private $birth_date;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="sex", type="string", length=10)
     */
    private $sex;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="phone", type="string", length=50)
     */
    private $phone;
    
    /**
     * @var date
     * 
     * @ORM\Column(name="creation_date", type="date")
     */
    private $creation_date;
    
    /**
     * @var update_date
     * 
     * @ORM\Column(name="update_date", type="date")
     */
    private $update_date;
    
    
    /**
     * @ORM\OneToMany(targetEntity="Addresse", mappedBy="User")
     */
    private $addresses;
    
    /**
     * @ORM\OneToOne(targetEntity="Tec\ServiceBundle\Entity\Media", inversedBy="user")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id")
     */
    private $media;


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
     * @return User
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     *
     * @return User
     */
    public function setBirthDate($birthDate)
    {
        $this->birth_date = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birth_date;
    }

    /**
     * Set sex
     *
     * @param string $sex
     *
     * @return User
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return User
     */
    public function setCreationDate($creationDate)
    {
        $this->creation_date = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creation_date;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     *
     * @return User
     */
    public function setUpdateDate($updateDate)
    {
        $this->update_date = $updateDate;

        return $this;
    }

    /**
     * Get updateDate
     *
     * @return \DateTime
     */
    public function getUpdateDate()
    {
        return $this->update_date;
    }

    /**
     * Add address
     *
     * @param \Tec\UserBundle\Entity\Addresse $address
     *
     * @return User
     */
    public function addAddress(\Tec\UserBundle\Entity\Addresse $address)
    {
        $this->addresses[] = $address;

        return $this;
    }

    /**
     * Remove address
     *
     * @param \Tec\UserBundle\Entity\Addresse $address
     */
    public function removeAddress(\Tec\UserBundle\Entity\Addresse $address)
    {
        $this->addresses->removeElement($address);
    }

    /**
     * Get addresses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * Set media
     *
     * @param \Tec\ServiceBundle\Media $media
     *
     * @return User
     */
    public function setMedia(\Tec\ServiceBundle\Media $media = null)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return \Tec\ServiceBundle\Media
     */
    public function getMedia()
    {
        return $this->media;
    }
}