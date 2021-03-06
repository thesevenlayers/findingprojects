<?php
namespace FP\BEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FP\BEBundle\Entity\Common\ImageUpload;

/**
 * @ORM\Entity(repositoryClass="FP\BEBundle\Repository\SponserRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="sponsers")
 */
class Sponser extends ImageUpload
{
    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $created_at;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $url;

    /**
     * @ORM\ManyToOne(targetEntity="Project")
     */
    protected $project;

    protected $cat_dir = "sponsers";

//
//    protected $thumb_dir= "thumb";
//
//    protected $thumb_h = 175;
//
//    protected $thumb_w = 234;

    /**
     * @ORM\PrePersist
     */
    public function setId()
    {
        $this->id = uniqid();
    }

    /**
     * @ORM\PrePersist
     */
    public function checkURL()
    {
        if($this->url)
        {
            if(!preg_match('/^(http:)?\/\//', $this->url))
            {
                $this->url = "http://" . $this->url;
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

    public function belongToProject()
    {
        return $this->getProject() !== null ? true : false;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Sponser
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set project
     *
     * @param \FP\BEBundle\Entity\Project $project
     * @return Sponser
     */
    public function setProject(\FP\BEBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \FP\BEBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }
}
