<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Choice;
use App\Entity\Quiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function getListByFilter(array $filters)
    {
        $qb = $this->createQueryBuilder('c');

        if (isset($filters['id']) && $filters['id']) {
            $qb->andWhere('u.id IN (:id)')->setParameter('id', $filters['id']);
        }
        if (isset($filters['quiz']) && $filters['quiz']) {
            $qb->andWhere('r.quiz = :quiz')->setParameter('quiz', $filters['quiz']);
        }
        if (isset($filters['user']) && $filters['user']) {
            $qb->andWhere('r.user = :user')->setParameter('user', $filters['user']);
        }

        return $qb->getQuery()->getResult();
    }

    public function add(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


}
