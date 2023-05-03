<?php

namespace App\Repository;

use App\Entity\ResulatQuiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ResulatQuiz>
 *
 * @method ResulatQuiz|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResulatQuiz|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResulatQuiz[]    findAll()
 * @method ResulatQuiz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResultatQuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResulatQuiz::class);
    }

    public function save(ResulatQuiz $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ResulatQuiz $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ResulatQuiz[] Returns an array of ResulatQuiz objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ResulatQuiz
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

function getRsultbyCompetitionName($nom)
{
    $subquery = $this->createQueryBuilder('rq2')
        ->select('COUNT(rq2.idQuiz)')
        ->where('rq2.idQuiz = rq.idQuiz')
        ->andWhere('rq2.score > rq.score')
        ->getDQL();

    return $this->createQueryBuilder('rq')
        ->innerJoin('rq.idQuiz', 'q')
        ->innerJoin('q.idCompetition', 'c')
        ->where('c.nom = :nom')
        ->setParameter('nom', $nom)
        ->andWhere(sprintf('(%s) < :rank', $subquery))
        ->setParameter('rank', 5)
        ->orderBy('q.idQuiz', 'ASC')
        ->addOrderBy('rq.score', 'DESC')
        ->getQuery()
        ->getResult();
}

}
