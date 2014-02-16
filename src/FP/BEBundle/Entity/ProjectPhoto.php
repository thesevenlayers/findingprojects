<?php
namespace FP\BEBundle\Entity;

use FP\BEBundle\Entity\Common\ImageUpload;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="FP\BEBundle\Repository\ProjectRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="project_photos")
 */
class ProjectPhoto extends ImageUpload
{
    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    protected $id;

    protected $cat_dir = "project_photos";

    protected $thumb_dir= "thumb";

    protected $thumb_h = 175;

    protected $thumb_w = 234;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="photos")
     */
    protected $project;

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
     * Set project
     *
     * @param \FP\BEBundle\Entity\Project $project
     * @return ProjectPhoto
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
