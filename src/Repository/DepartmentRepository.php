<?php

namespace App\Repository;

use App\Entity\Department;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Department>
 *
 * @method Department|null find($id, $lockMode = null, $lockVersion = null)
 * @method Department|null findOneBy(array $criteria, array $orderBy = null)
 * @method Department[]    findAll()
 * @method Department[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepartmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Department::class);
    }

    public function getListByFilter(array $filters)
    {
        $qb = $this->createQueryBuilder('d');

        if (isset($filters['id']) && $filters['id']) {
            $qb->andWhere('d.id IN (:id)')->setParameter('id', $filters['id']);
        }

        return $qb->getQuery()->getResult();
    }

    public function getListQuery(Request $request): QueryBuilder
    {
//        if ($showRemoved) {
//            $this->getEntityManager()->getFilters()->disable('softdeleteable');
//        }

        $filters = json_decode($request->get('filter', ''), true);

        $query = $this->createQueryBuilder('d');
        if (isset($filters['id']) && $filters['id']) {
            $query->andWhere('d.id IN (:id)')->setParameter('id', $filters['id']);
        }

        if (isset($filters['sort']) && isset($filters['order'])) {
            $query->orderBy('d.' . $filters['sort'], $filters['order']);
        }

        if (isset($filters['name']) && $filters['name']) {
            $query->andWhere('LOWER(d.name) LIKE :name')->setParameter('name', '%'.mb_strtolower($filters['name']).'%');
        }

        return $query;
    }

    public function add(Department $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Department $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}
