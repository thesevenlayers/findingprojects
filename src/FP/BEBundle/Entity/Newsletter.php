<?php
namespace FP\BEBundle\Entity;

use FP\BEBundle\Entity\Common\FileUpload;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="newsletter")
 */
class Newsletter
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
    protected $description;

    /**
     * @ORM\OneToMany(targetEntity="NewsletterEmail", mappedBy="newsletter")
     */
    protected $emails;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->emails = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     */
    public function setId($id)
    {
        $this->id = uniqid();
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
     * Set description
     *
     * @param string $description
     * @return Newsletter
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add emails
     *
     * @param \FP\BEBundle\Entity\NewsletterEmail $emails
     * @return Newsletter
     */
    public function addEmail(\FP\BEBundle\Entity\NewsletterEmail $emails)
    {
        $this->emails[] = $emails;

        return $this;
    }

    /**
     * Remove emails
     *
     * @param \FP\BEBundle\Entity\NewsletterEmail $emails
     */
    public function removeEmail(\FP\BEBundle\Entity\NewsletterEmail $emails)
    {
        $this->emails->removeElement($emails);
    }

    /**
     * Get emails
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmails()
    {
        return $this->emails;
    }
}
