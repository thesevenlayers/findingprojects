<?php
namespace FP\BEBundle\Services\DomainManagers;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;

class BasicDomainManager
{
    public $em;
    public $error = null;
    public $search_arr = array("artist", "project");
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function saveRecord($obj, $cropping)
    {
        try
        {
            if($cropping)
            {
                $tempDim["temp_height"] = $cropping["temp_height"];
                $tempDim["temp_width"] = $cropping["temp_width"];
                $cropCoord["height"] = $cropping["height"];
                $cropCoord["width"] = $cropping["width"];
                $cropCoord["x"] = $cropping["x"];
                $cropCoord["y"] = $cropping["y"];

                $obj->setTempImgSize($tempDim);
                $obj->setCropCoordinates($cropCoord);
            }
            $this->em->persist($obj);
            $this->em->flush();
        }
        catch(ORMException $e)
        {
            $this->error = $e->getMessage();
            return false;
        }

        return $obj;
    }

    public function removeRecord($obj)
    {
        try{
            $this->em->remove($obj);
            $this->em->flush();
        }
        catch(ORMException $e)
        {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function search($search_query, $target_entity)
    {
        if(in_array(strtolower($target_entity), $this->search_arr))
        {
            $entities = $this->em->getRepository("FPBEBundle:".ucfirst($target_entity))->searchByCriteria($search_query);
            return $entities;
        }

        return null;
    }
}