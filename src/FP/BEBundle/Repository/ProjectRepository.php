<?php
namespace FP\BEBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class ProjectRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array("created_at" => "desc"));
    }

    public function searchByCriteria($search_query)
    {
        $q = $this->createQueryBuilder("a");
        $q->where($q->expr()->like("a.title", $q->expr()->literal("%{$search_query}%")));
        return $q->getQuery()->execute();
    }
}