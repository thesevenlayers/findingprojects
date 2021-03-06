<?php
namespace FP\BEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="FP\BEBundle\Repository\ProjectRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="projects")
 */
class Project
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
    protected $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $created_at;

    /**
     * @ORM\ManyToMany(targetEntity="Artist")
     */
    protected $artists;

    /**
     * @ORM\OneToMany(targetEntity="ProjectPhoto", mappedBy="project", cascade={"persist", "remove"})
     */
    protected $photos;

    /**
     * @ORM\OneToMany(targetEntity="ProjectFile", mappedBy="project", cascade={"persist", "remove"})
     */
    protected $files;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $video;

    /**
     * @ORM\OneToMany(targetEntity="Sponser", mappedBy="project", cascade={"persist", "remove"})
     */
    protected $sponsers;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->artists = new \Doctrine\Common\Collections\ArrayCollection();
        $this->photos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function firstPhoto()
    {
        if($this->photos)
        {
            return $this->photos[0];
        }

        return null;
    }

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
    public function setCreatedAtValue()
    {
        $this->created_at = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }


    /**
     * Set description
     *
     * @param string $description
     * @return Project
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
     * Add artists
     *
     * @param \FP\BEBundle\Entity\Artist $artists
     * @return Project
     */
    public function addArtist(\FP\BEBundle\Entity\Artist $artists)
    {
        $this->artists[] = $artists;

        return $this;
    }

    /**
     * Remove artists
     *
     * @param \FP\BEBundle\Entity\Artist $artists
     */
    public function removeArtist(\FP\BEBundle\Entity\Artist $artists)
    {
        $this->artists->removeElement($artists);
    }

    /**
     * Get artists
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArtists()
    {
        return $this->artists;
    }

    /**
     * Add photos
     *
     * @param \FP\BEBundle\Entity\ProjectPhoto $photos
     * @return Project
     */
    public function addPhoto(\FP\BEBundle\Entity\ProjectPhoto $photos)
    {
        $this->photos[] = $photos;
        $photos->setProject($this);
        return $this;
    }

    /**
     * Remove photos
     *
     * @param \FP\BEBundle\Entity\ProjectPhoto $photos
     */
    public function removePhoto(\FP\BEBundle\Entity\ProjectPhoto $photos)
    {
        $this->photos->removeElement($photos);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Set video
     *
     * @param string $video
     * @return Project
     */
    public function setVideo($video)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     * @return string 
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Add files
     *
     * @param \FP\BEBundle\Entity\ProjectFile $files
     * @return Project
     */
    public function addFile(\FP\BEBundle\Entity\ProjectFile $files)
    {
        $this->files[] = $files;
        $files->setProject($this);
        return $this;
    }

    /**
     * Remove files
     *
     * @param \FP\BEBundle\Entity\ProjectFile $files
     */
    public function removeFile(\FP\BEBundle\Entity\ProjectFile $files)
    {
        $this->files->removeElement($files);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Add sponsers
     *
     * @param \FP\BEBundle\Entity\Sponser $sponsers
     * @return Project
     */
    public function addSponser(\FP\BEBundle\Entity\Sponser $sponsers)
    {
        $this->sponsers[] = $sponsers;
        $sponsers->setProject($this);
        return $this;
    }

    /**
     * Remove sponsers
     *
     * @param \FP\BEBundle\Entity\Sponser $sponsers
     */
    public function removeSponser(\FP\BEBundle\Entity\Sponser $sponsers)
    {
        $this->sponsers->removeElement($sponsers);
    }

    /**
     * Get sponsers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSponsers()
    {
        return $this->sponsers;
    }
}
