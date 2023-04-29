<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineExtensions\Query\Mysql\DateFormat;

/**
 * @extends ServiceEntityRepository<Evenement>
 *
 * @method Evenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenement[]    findAll()
 * @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

    public function save(Evenement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Evenement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function SearchByLieu($lieu)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.lieu = :location')
            ->setParameter('location', $lieu)
            ->getQuery()
            ->getResult();
    }
    public function countEventsPerMonth()
{
    $qb = $this->createQueryBuilder('e');
    $qb->select("DATE_FORMAT(e.date, '%m-%Y') as month_year, COUNT(e.idEvenement) as num_events")
       ->where("YEAR(e.date) = YEAR(CURRENT_DATE())")
       ->groupBy("month_year")
       ->orderBy("e.date", "ASC");

    $query = $qb->getQuery();
    $query->setHint(\Doctrine\ORM\Query::HINT_INCLUDE_META_COLUMNS, true);
    $resultSetMapping = new ResultSetMappingBuilder($this->getEntityManager());
    $resultSetMapping->addScalarResult('month_year', 'month_year');
    $resultSetMapping->addScalarResult('num_events', 'num_events');
    $query->setResultSetMapping($resultSetMapping);

    return $query->getResult();
}



//    /**
//     * @return Evenement[] Returns an array of Evenement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Evenement
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
