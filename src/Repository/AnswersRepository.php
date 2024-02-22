<?php

namespace App\Repository;

use App\Entity\Answer;
use App\Entity\Choice;
use App\Entity\Quiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Answer>
 *
 * @method Answer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Answer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Answer[]    findAll()
 * @method Answer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Answer::class);
    }

    public function add(Answer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Answer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAnswersByQuiz(Quiz $quiz): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.quiz = :quiz')
            ->setParameter('quiz', $quiz)
            ->getQuery()
            ->getResult();
    }

    public function getListByFilter(array $filters)
    {
        $qb = $this->createQueryBuilder('a')->leftJoin('a.result', 'r');

        $qb->where('a.type = :type')->setParameter('type', Choice::TYPE_TEXT);
        $qb->andWhere('a.score is NULL');

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

}
