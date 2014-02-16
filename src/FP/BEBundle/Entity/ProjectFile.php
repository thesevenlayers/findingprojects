<?php
namespace FP\BEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FP\BEBundle\Entity\Common\FileUpload;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="projects_files")
 */
class ProjectFile extends FileUpload
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
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="files")
     */
    protected $project;


    /**
     * @ORM\PrePersist
     */
    public function setId()
    {
        $this->id = uniqid();
    }

    public function getId()
    {
        return $this->id;
    }

//    /**
//     * @ORM\PreUpdate
//     * @ORM\PreRemove
//     */
//    public function setFolderName()
//    {
//        parent::setFileDir($this->id);
//    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->created_at = new \DateTime();
    }

    /**
     * @ORM\PostLoad
     */
    public function setFileDirValue()
    {
        $this->setFileDir($this->getProject()->getId());
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


    /**
     * Set project
     *
     * @param \FP\BEBundle\Entity\Project $project
     * @return ProjectFile
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
