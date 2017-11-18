<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TaskRepository extends EntityRepository
{
  public function getTasksByStatus($status, $author)
    {
        return $query = $this->getEntityManager()
            ->getRepository('AppBundle:Task')
            ->createQueryBuilder('t')
            ->where("t.status = :status")
            ->setParameter("status", $status)
            ->andWhere('t.author = :author')
            ->setParameter("author", $author)
            ->getQuery()
            ->getResult()
        ;
    }
}
