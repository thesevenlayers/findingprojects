<?php
namespace FP\BEBundle\Repository;

use Doctrine\ORM\EntityRepository;

class HomeItemRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array("created_at" => "desc"));
    }

}