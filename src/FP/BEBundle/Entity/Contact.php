<?php
namespace FP\BEBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="contact")
 */
class Contact
{
    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    protected $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $address;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $phone;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $facebook;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $vimeo;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $zip;

    /**
     * @ORM\PrePersist
     */
    public function setId($id)
    {
        $this->id = uniqid();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function checkURL()
    {
        if($this->facebook)
        {
            if(!preg_match('/^(http:)?\/\//', $this->facebook))
            {
                $this->facebook = "http://" . $this->facebook;
            }
        }

        if($this->vimeo)
        {
            if(!preg_match('/^(http:)?\/\//', $this->vimeo))
            {
                $this->vimeo = "http://" . $this->vimeo;
            }
        }
    }
    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set address
     *
     * @param string $address
     * @return Contact
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Contact
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
     * Set email
     *
     * @param string $email
     * @return Contact
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set facebook
     *
     * @param string $facebook
     * @return Contact
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get facebook
     *
     * @return string 
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Set vimeo
     *
     * @param string $vimeo
     * @return Contact
     */
    public function setVimeo($vimeo)
    {
        $this->vimeo = $vimeo;

        return $this;
    }

    /**
     * Get vimeo
     *
     * @return string 
     */
    public function getVimeo()
    {
        return $this->vimeo;
    }

    /**
     * Set zip
     *
     * @param string $zip
     * @return Contact
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
     *
     * @return string 
     */
    public function getZip()
    {
        return $this->zip;
    }
}
