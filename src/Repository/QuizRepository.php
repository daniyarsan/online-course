<?php

namespace App\Repository;

use App\Entity\Quiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Quiz>
 *
 * @method Quiz|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quiz|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quiz[]    findAll()
 * @method Quiz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quiz::class);
    }

    public function getListByFilter(array $filters)
    {
        $qb = $this->createQueryBuilder('q')
            ->innerJoin('q.departments','d');

        if (isset($filters['id']) && $filters['id']) {
            $qb->andWhere('q.id IN (:id)')->setParameter('id', $filters['id']);
        }
        if (isset($filter['title']) && $filter['title']) {
            $qb->andWhere('LOWER(q.title) LIKE :title')->setParameter('title', '%'.mb_strtolower($filter['title']).'%');
        }
        if (isset($filters['department']) && $filters['department']) {
            $qb->andWhere('d.id = :id')->setParameter('id', $filters['department']);
        }
        if (isset($filters['category']) && $filters['category']) {
            $qb->andWhere('q.category = :category')->setParameter('category', $filters['category']);
        }

        return $qb->getQuery()->getResult();
    }

    public function add(Quiz $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Quiz $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getQuizCollectionByDepartmentId(int $id, array $passedQuizIds = []): array
    {
        $qb = $this->createQueryBuilder('q')->leftJoin('q.departments', 'd');

        $qb->where('d.id = :id')->setParameter('id', $id);

        if (!empty($passedQuizIds)) {
            $qb->andWhere('q.id NOT IN (:ids)')->setParameter('ids', $passedQuizIds);
        }

        return $qb->getQuery()->getResult();
    }

}
