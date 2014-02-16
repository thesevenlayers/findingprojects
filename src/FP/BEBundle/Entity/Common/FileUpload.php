<?php
namespace FP\BEBundle\Entity\Common;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class FileUpload
{
    private $file_dir = "";

    protected $file_file;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $file_name;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updated_at;

    public function setFileDir($file_dir = "")
    {
        $this->file_dir = $file_dir;
    }

    public function getFileDir()
    {
        return $this->file_dir;
    }

    public function getUploadDir()
    {
        return 'uploads'."/".'files'."/".$this->file_dir;
    }

    public function getRootUploadDir()
    {
//        return $_SERVER['DOCUMENT_ROOT']."/SymfonyFP/finding_projects/web/".$this->getUploadDir();
        return $_SERVER['DOCUMENT_ROOT']."/".$this->getUploadDir();
    }

    public function getWebPath()
    {
        return $this->file_name === null ? null : $this->getUploadDir()."/".$this->file_name;
    }

    public function getAbsolutePath()
    {
        return $this->file_name === null ? null : $this->getRootUploadDir()."/".$this->file_name;
    }

    /**
     * WARNING!! PreUpdate no fired since $file_file is not managed by Doctrine.
     * SOLUTION: use PostLoad() Event.
     * @ORM\PostLoad()
     */
    public function postLoad()
    {
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if($this->file_file != null)
        {
            if($this->file_name != null)
            {
                if(file_exists($this->getAbsolutePath()))
                {
                    unlink($this->getAbsolutePath());
                }
            }
//            die(var_dump(gettype($this->file_file)));
//            $file = new UploadedFile();
//            $file->isFile()
            $this->file_name = $this->file_file->getClientOriginalName();
//            $this->file_name = uniqid().".".$this->file_file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if($this->file_file === null)
        {
            return;
        }

        if(!file_exists($this->getUploadDir()))
        {
            $old_umask = umask(0);
            mkdir($this->getUploadDir(),0777, true);
            umask($old_umask);
        }

        if($this->file_name)
        {
            $this->file_file->move($this->getUploadDir(), $this->file_name);
        }

        unset($this->file_file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if($this->getAbsolutePath() !== null){
            if(file_exists($this->getAbsolutePath()))
            {
                unlink($this->getAbsolutePath());
            }
        }
    }

    public function setFileName($file_name)
    {
        $this->file_name = $file_name;
        return $this;
    }

    public function getFileName()
    {
        return $this->file_name;
    }

    public function setFileFile(UploadedFile $file)
    {
        $this->file_file = $file;
        return $this;
    }

    public function getFileFile()
    {
        return $this->file_file;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = new \DateTime();
        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
}
