<?php
namespace FP\BEBundle\Entity;

use FP\BEBundle\Entity\Common\ImageUpload;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="FP\BEBundle\Repository\HomeItemRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="home_items")
 */
class HomeItem extends ImageUpload
{
    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    protected $id;

    protected $cat_dir = "home_item";

    protected $thumb_dir = "thumbs";

    protected $thumb_w = 88;

    protected $thumb_h = 88;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $artist_name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $project_name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

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
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set artist_name
     *
     * @param string $artistName
     * @return HomeItem
     */
    public function setArtistName($artistName)
    {
        $this->artist_name = $artistName;

        return $this;
    }

    /**
     * Get artist_name
     *
     * @return string 
     */
    public function getArtistName()
    {
        return $this->artist_name;
    }

    /**
     * Set project_name
     *
     * @param string $projectName
     * @return HomeItem
     */
    public function setProjectName($projectName)
    {
        $this->project_name = $projectName;

        return $this;
    }

    /**
     * Get project_name
     *
     * @return string 
     */
    public function getProjectName()
    {
        return $this->project_name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return HomeItem
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
