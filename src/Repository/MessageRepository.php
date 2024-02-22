<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Message>
 *
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }


    public function getListByFilter(array $filter)
    {
        return $this->getListQueryByFilter($filter)->getQuery()->getResult();
    }

    public function getListQueryByFilter(array $filter): QueryBuilder
    {

        $query = $this->createQueryBuilder('m');

        if (isset($filter['q'])) {
            $query->leftJoin('m.user', 'u');
            $query->andWhere('u.username LIKE :searchString')->setParameter('searchString', '%'.$filter['q'].'%');
        }

        if (isset($filter['sort']) && isset($filter['order'])) {
            $query->orderBy('m.' . $filter['sort'], $filter['order']);
        }

        if (isset($filter['id']) && $filter['id']) {
            $query->andWhere('m.id = :id')->setParameter('id', $filter['id']);
        }
        if (isset($filter['name']) && $filter['name']) {
            $query->andWhere('LOWER(m.name) LIKE :name')->setParameter('name', '%'.mb_strtolower($filter['name']).'%');
        }

        return $query;
    }

}
