<?php

namespace App\Repository;

use App\Entity\Filterable;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getListByFilter(array $filters)
    {
        $qb = $this->createQueryBuilder('u')->leftJoin('u.results', 'r');

        if (isset($filters['id']) && $filters['id']) {
            $qb->andWhere('u.id IN (:id)')->setParameter('id', $filters['id']);
        }
        if (isset($filters['name']) && $filters['name']) {
            $qb->andWhere('LOWER(u.name) LIKE :name')->setParameter('name', '%'.mb_strtolower($filters['name']).'%');
        }
        if (isset($filters['quiz']) && $filters['quiz']) {
            $qb->andWhere('r.quiz = :quiz')->setParameter('quiz', $filters['quiz']);
        }

        return $qb->getQuery()->getResult();
    }

}
