<?php
namespace Gbr\BEBundle\Entity\Common;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
class FileUploadTwo
{
    protected $cat_dir = "";

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updated_at;

    /**
     * @Assert\Image
     */
    protected $banner_photo_file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $banner_photo_image;

    /**
     * @Assert\Image
     */
    protected $side_photo_file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $side_photo_image;

    protected $crop_coordinates = array();

    protected $banner_tmp_img_size = array();

    protected $side_tmp_img_size = array();

    public function getBannerTmpImgSize()
    {
        return $this->banner_tmp_img_size;
    }

    public function setBannerTmpImgSize($tmp_size)
    {
        $this->banner_tmp_img_size = $tmp_size;
        return $this;
    }

    public function getSideTmpImgSize()
    {
        return $this->side_tmp_img_size;
    }

    public function setSideTmpImgSize($tmp_size)
    {
        $this->side_tmp_img_size = $tmp_size;
        return $this;
    }

    public function setCropCoordinates($crop_coordinates)
    {
        $this->crop_coordinates = $crop_coordinates;
        return $this;
    }

    public function getCropCoordinates()
    {
        return $this->crop_coordinates;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return EatDrink
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get updated_at
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set banner_photo_image
     *
     * @param string $bannerPhotoImage
     * @return EatDrink
     */
    public function setBannerPhotoImage($bannerPhotoImage)
    {
        $this->banner_photo_image = $bannerPhotoImage;

        return $this;
    }

    /**
     * Get banner_photo_image
     *
     * @return string
     */
    public function getBannerPhotoImage()
    {
        return $this->banner_photo_image;
    }

    /**
     * Set side_photo_image
     *
     * @param string $sidePhotoImage
     * @return EatDrink
     */
    public function setSidePhotoImage($sidePhotoImage)
    {
        $this->side_photo_image = $sidePhotoImage;

        return $this;
    }

    /**
     * Get side_photo_image
     *
     * @return string
     */
    public function getSidePhotoImage()
    {
        return $this->side_photo_image;
    }

    public function setBannerPhotoFile($file)
    {
        $this->banner_photo_file = $file;
        return $this;
    }

    public function getBannerPhotoFile()
    {
        return $this->banner_photo_file;
    }


    public function setSidePhotoFile($file)
    {
        $this->side_photo_file = $file;
        return $this;
    }

    public function getSidePhotoFile()
    {
        return $this->side_photo_file;
    }

    public function getUploadDir()
    {
        return 'uploads'."/".'images'."/".$this->cat_dir;
    }

    public function getRootUploadDir()
    {
        return $_SERVER['DOCUMENT_ROOT']."/"."gabrielnew"."/"."gabriel-final"."/"."web"."/".$this->getUploadDir();
    }

    public function getBannerWebPath()
    {
        return $this->banner_photo_image === null ? null : $this->getUploadDir()."/".$this->banner_photo_image;
    }

    public function getBannerAbsolutePath()
    {
        return $this->banner_photo_image === null ? null : $this->getRootUploadDir()."/".$this->banner_photo_image;
    }

    public function getSideWebPath()
    {
        return $this->side_photo_image === null ? null : $this->getUploadDir()."/".$this->side_photo_image;
    }

    public function getSideAbsolutePath()
    {
        return $this->side_photo_image === null ? null : $this->getRootUploadDir()."/".$this->side_photo_image;
    }

    /**
     * WARNING!! PreUpdate no fired since $file is not managed by Doctrine.
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
        if($this->banner_photo_file !== null)
        {
            if($this->banner_photo_image !== null)
            {
                if(file_exists($this->getBannerAbsolutePath()))
                {
                    unlink($this->getBannerAbsolutePath());
                }
            }
            $this->banner_photo_image = uniqid().".".$this->banner_photo_file->guessExtension();
        }

        if($this->side_photo_file!== null)
        {
            if($this->side_photo_image !== null)
            {
                if(file_exists($this->getSideAbsolutePath()))
                {
                    unlink($this->getSideAbsolutePath());
                }
            }
            $this->side_photo_image = uniqid().".".$this->side_photo_file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function uploadBanner()
    {
        if($this->banner_photo_file === null)
        {
            return;
        }

        if(!file_exists($this->getUploadDir()))
        {
            mkdir($this->getUploadDir(),0777, true);
        }

        $this->banner_photo_file->move($this->getUploadDir(), $this->banner_photo_image);

        list($original_width, $original_height, $img_type) = getimagesize($this->getBannerAbsolutePath());
        $src = null;
        switch($img_type)
        {
            case IMAGETYPE_JPEG:
                $src = \imagecreatefromjpeg($this->getBannerAbsolutePath());
                break;
            case IMAGETYPE_PNG:
                $src = \imagecreatefrompng($this->getBannerAbsolutePath());
                break;
            case IMAGETYPE_GIF:
                $src = \imagecreatefromgif($this->getBannerAbsolutePath());
                break;
            default:
                $src = null;
                break;
        }


        if($src)
        {
            $dst = imagecreatetruecolor($this->crop_coordinates["banner_width"], $this->crop_coordinates["banner_height"]);
        }

        if($this->banner_tmp_img_size["banner_tmp_width"] == 0)
        {
            \imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));
            \imagealphablending($dst, TRUE);
            \imagecopyresized($dst, $src, 0, 0, 0, 0, $this->crop_coordinates["banner_width"], $this->crop_coordinates["banner_height"], $original_width, $original_height);
        }
        else
        {
            $tmp_org = imagecreatetruecolor($this->banner_tmp_img_size["banner_tmp_width"], $this->banner_tmp_img_size["banner_tmp_height"]);

            \imagecopyresized($tmp_org, $src, 0, 0, 0, 0, $this->banner_tmp_img_size["banner_tmp_width"], $this->banner_tmp_img_size["banner_tmp_height"], $original_width, $original_height);
            \imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));
            \imagealphablending($dst, TRUE);
            \imagecopy($dst, $tmp_org, 0, 0, $this->crop_coordinates["banner_x"], $this->crop_coordinates["banner_y"], $this->banner_tmp_img_size["banner_tmp_width"], $this->banner_tmp_img_size["banner_tmp_height"]);
        }
//
//        switch($img_type)
//        {
//            case IMAGETYPE_JPEG:
//                \imagejpeg($dst, $this->getBannerAbsolutePath());
//                break;
//            case IMAGETYPE_PNG:
//                \imagepng($dst, $this->getBannerAbsolutePath());
//                break;
//            case IMAGETYPE_GIF:
//                \imagegif($dst, $this->getBannerAbsolutePath());
//                break;
//        }

        \imagejpeg($dst, $this->getBannerAbsolutePath(), 80);

        unset($this->banner_photo_file);
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function uploadSide()
    {
        if($this->side_photo_file === null)
        {
            return;
        }

        if(!file_exists($this->getUploadDir()))
        {
            mkdir($this->getUploadDir(),0777, true);
        }

        $this->side_photo_file->move($this->getUploadDir(), $this->side_photo_image);


        list($original_width, $original_height, $img_type) = getimagesize($this->getSideAbsolutePath());
        $src = null;
        switch($img_type)
        {
            case IMAGETYPE_JPEG:
                $src = \imagecreatefromjpeg($this->getSideAbsolutePath());
                break;
            case IMAGETYPE_PNG:
                $src = \imagecreatefrompng($this->getSideAbsolutePath());
                break;
            case IMAGETYPE_GIF:
                $src = \imagecreatefromgif($this->getSideAbsolutePath());
                break;
            default:
                $src = null;
                break;
        }


        if($src)
        {
            $dst = imagecreatetruecolor($this->crop_coordinates["side_width"], $this->crop_coordinates["side_height"]);
        }

        if($this->side_tmp_img_size["side_tmp_width"] == 0)
        {
            \imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));
            \imagealphablending($dst, TRUE);
            \imagecopyresized($dst, $src, 0, 0, 0, 0, $this->crop_coordinates["side_width"], $this->crop_coordinates["side_height"], $original_width, $original_height);
        }
        else
        {
            $tmp_org = imagecreatetruecolor($this->side_tmp_img_size["side_tmp_width"], $this->side_tmp_img_size["side_tmp_height"]);
            \imagecopyresized($tmp_org, $src, 0, 0, 0, 0, $this->side_tmp_img_size["side_tmp_width"], $this->side_tmp_img_size["side_tmp_height"], $original_width, $original_height);
            \imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));
            \imagealphablending($dst, TRUE);
            \imagecopy($dst, $tmp_org, 0, 0, $this->crop_coordinates["side_x"], $this->crop_coordinates["side_y"], $this->side_tmp_img_size["side_tmp_width"], $this->side_tmp_img_size["side_tmp_height"]);
        }

//        switch($img_type)
//        {
//            case IMAGETYPE_JPEG:
//                \imagejpeg($dst, $this->getSideAbsolutePath());
//                break;
//            case IMAGETYPE_PNG:
//                \imagepng($dst, $this->getSideAbsolutePath());
//                break;
//            case IMAGETYPE_GIF:
//                \imagegif($dst, $this->getSideAbsolutePath());
//                break;
//        }

        \imagejpeg($dst, $this->getSideAbsolutePath(), 80);


        unset($this->side_photo_file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if($this->getBannerAbsolutePath() !== null){
            if(file_exists($this->getBannerAbsolutePath()))
            {
                unlink($this->getBannerAbsolutePath());
            }
        }

        if($this->getSideAbsolutePath() !== null){
            if(file_exists($this->getSideAbsolutePath()))
            {
                unlink($this->getSideAbsolutePath());
            }
        }
    }


}