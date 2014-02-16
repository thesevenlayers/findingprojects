<?php
namespace FP\BEBundle\Repository;

use Doctrine\ORM\EntityRepository;

class SponserRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array("created_at" => "desc"));
    }

    public function getGlobalSponsers()
    {
        $q = $this->createQueryBuilder("s")
                    ->where("s.project IS NULL")
                    ->getQuery();

        return $q->getResult();
    }
}