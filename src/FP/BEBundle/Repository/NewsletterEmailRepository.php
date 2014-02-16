<?php
namespace FP\BEBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class NewsletterEmailRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array("created_at" => "desc"));
    }

    public function getNewEmailsNumber()
    {
        $q = $this->createQueryBuilder("e")
            ->select("count(e.address)")
            ->where("e.notify = :notify")
            ->setParameter("notify", true)
            ->setMaxResults(1)
            ->getQuery();

        try
        {
            return $q->getSingleScalarResult();
        }
        catch (NoResultException $e)
        {
            return null;
        }
    }

    public function markEmailsRead()
    {
        $q = $this->createQueryBuilder("e")
            ->update()
            ->set("e.notify", ":new_notify")
            ->setParameter("new_notify", false)
            ->where("e.notify = :notify")
            ->setParameter("notify", true)
            ->getQuery();

        $q->execute();
    }
}