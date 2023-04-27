<?php

namespace App\Repository;

use App\Entity\Competition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Competition>
 *
 * @method Competition|null find($id, $lockMode = null, $lockVersion = null)
 * @method Competition|null findOneBy(array $criteria, array $orderBy = null)
 * @method Competition[]    findAll()
 * @method Competition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompetitionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Competition::class);
    }

    public function save(Competition $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Competition $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Competition[] Returns an array of Competition objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Competition
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    function SearchByNom($nom)
    {
        return $this->createQueryBuilder('c')
            ->where('c.nom LIKE :m')
            ->orWhere('c.recompense LIKE :r')
            ->setParameter('m', '%' . $nom . '%')
            ->setParameter('r', '%' . $nom . '%')
            ->getQuery();
    }
    public function findCompetitionsOpenedThisWeek()
    {
        $dateDebutSemaine = new \DateTime('Monday this week');
        $dateFinSemaine = new \DateTime('Sunday this week');

        return $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.dateDebut <= :dateFinSemaine')
            ->andWhere('c.dateFin >= :dateDebutSemaine')
            ->setParameter('dateDebutSemaine', $dateDebutSemaine)
            ->setParameter('dateFinSemaine', $dateFinSemaine)
            ->getQuery()
            ->getResult();
    }
    public function findCompetitionsOpenedThisWeekWithWinnersAndRewards()
    {
        
        //$startDate = new \DateTime('Monday this week');
       // $endDate = new \DateTime('Sunday this week');
        $startDate = new \DateTime('2023-04-24');
        $endDate = new \DateTime('2023-04-30');

        return $this->createQueryBuilder('c')
            ->select('c.nom as competition_name', 'c.recompense as competition_reward', 'l.titre as book_title', 'MAX(r.score) as winner_score', 'IDENTITY(r.idClient) as winner_client_id')
            ->join('App\Entity\Quiz', 'q', 'WITH', 'q.idCompetition = c.idCompetition')
            ->join('App\Entity\ResulatQuiz', 'r', 'WITH', 'r.idQuiz = q.idQuiz')
            ->join('App\Entity\Livre', 'l', 'WITH', 'l.idLivre = c.idLivre')
            ->andWhere('c.dateDebut >= :startDate')
            ->andWhere('c.dateFin <= :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->groupBy('c.idCompetition')
            ->getQuery()
            ->getResult();
    }
}
