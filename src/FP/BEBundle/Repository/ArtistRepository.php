<?php
namespace FP\BEBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class ArtistRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array("created_at" => "desc"));
    }

    public function searchByCriteria($search_query)
    {
        $q = $this->createQueryBuilder("a");
        $q->where($q->expr()->like("a.artist_name", $q->expr()->literal("%{$search_query}%")));
        return $q->getQuery()->execute();
    }

    public function findByOneArtistName($query)
    {
        $q = $this->createQueryBuilder("a")
                ->where("a.artist_name = :artist")
                ->setParameter("artist", $query)
                ->setMaxResults(1)
                ->getQuery()
            ;
        try
        {
            return $q->getSingleResult();
        } catch(NoResultException $e)
        {
            return null;
        }
    }
}