<?php

namespace App\Repository;

use App\Entity\Result;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Result>
 *
 * @method Result|null find($id, $lockMode = null, $lockVersion = null)
 * @method Result|null findOneBy(array $criteria, array $orderBy = null)
 * @method Result[]    findAll()
 * @method Result[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResultsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Result::class);
    }

    public function getListByFilter(array $filters)
    {
        $qb = $this->createQueryBuilder('r');

        if (isset($filters['id']) && $filters['id']) {
            $qb->andWhere('r.id IN (:id)')->setParameter('id', $filters['id']);
        }
        if (isset($filters['user']) && $filters['user']) {
            $qb->andWhere('r.user = :user')->setParameter('user', $filters['user']);
        }
        if (isset($filters['quiz']) && $filters['quiz']) {
            $qb->andWhere('r.quiz = :quiz')->setParameter('quiz', $filters['quiz']);
        }

        return $qb->getQuery()->getResult();
    }

    public function remove(Result $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
