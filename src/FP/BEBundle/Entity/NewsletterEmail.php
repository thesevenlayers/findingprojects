<?php
namespace FP\BEBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="FP\BEBundle\Repository\NewsletterEmailRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="newsletter_emails")
 */
class NewsletterEmail
{
    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Newsletter", cascade={"persist"})
     */
    protected $newsletter;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $address;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $notify;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $created_at;

    /**
     * @ORM\PrePersist
     */
    public function setId($id)
    {
        $this->id = uniqid();
    }

    /**
     * @ORM\PrePersist
     */
    public function setNotifyValue()
    {
        $this->setNotify(true);
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
     * Set newsletter
     *
     * @param \FP\BEBundle\Entity\Newsletter $newsletter
     * @return NewsletterEmail
     */
    public function setNewsletter(\FP\BEBundle\Entity\Newsletter $newsletter = null)
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    /**
     * Get newsletter
     *
     * @return \FP\BEBundle\Entity\Newsletter 
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return NewsletterEmail
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
     * Set notify
     *
     * @param boolean $notify
     * @return NewsletterEmail
     */
    public function setNotify($notify)
    {
        $this->notify = $notify;

        return $this;
    }

    /**
     * Get notify
     *
     * @return boolean 
     */
    public function getNotify()
    {
        return $this->notify;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->created_at = new \DateTime();
    }


    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Project
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
}
