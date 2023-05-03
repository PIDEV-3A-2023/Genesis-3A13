<?php

namespace App\Repository;

use App\Entity\Fidelite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Commande;
use App\Entity\Utilisateur;

/**
 * @extends ServiceEntityRepository<Fidelite>
 *
 * @method Fidelite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fidelite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fidelite[]    findAll()
 * @method Fidelite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FideliteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fidelite::class);
    }

    public function save(Fidelite $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Fidelite $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Fidelite[] Returns an array of Fidelite objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Fidelite
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function findOneByIdClient(int $idClient): ?Fidelite
{
    return $this->createQueryBuilder('f')
        ->andWhere('f.idClient = :idClient')
        ->setParameter('idClient', $idClient)
        ->getQuery()
        ->getOneOrNullResult();
}

public function calculateTotalAchatByIdClient($idClient)
{
    $qb = $this->getEntityManager()->createQueryBuilder();

    $qb->select('SUM(c.montant) as total_achat')
       ->from('App\Entity\Commande', 'c')
       ->where('c.idClient = :id_client')
       ->setParameter('id_client', $idClient);

    $result = $qb->getQuery()->getSingleScalarResult();

    return $result;
}
public function findFideliteByIdClient($idClient)
    {
        return $this->createQueryBuilder('f')
            ->where('f.idClient = :idClient')
            ->setParameter('idClient', $idClient)
            ->getQuery()
            ->getOneOrNullResult();
    }

public function checkUserFidelite(int $userId): bool
{
    $fideliteRepository = $this->entityManager->getRepository(Fidelite::class);
    $userFidelite = $fideliteRepository->findOneBy(['user' => $userId]);

    if ($userFidelite === null) {
        return false;
    }

    return true;
}
public function findAllClients()
{
    $queryBuilder = $this->createQueryBuilder('f');
    $queryBuilder->select('u')
        ->innerJoin('App\Entity\Utilisateur', 'u', 'WITH', 'f.idClient = u.idUtilisateur');

    $result = $queryBuilder->getQuery()->getResult();

    return $result;
}

public function getUtilisateursSansFidelite()
{
   return $this->createQueryBuilder('f')
    ->leftJoin('Fidelite as f', 'c.idClient', '=', 'f.idClient')
    ->select('c.idClient')
    ->whereNull('f.idClient')
    ->distinct()
    ->get();


}

public function getNumberOfRows()
{
    $queryBuilder = $this->createQueryBuilder('fidelite');
    $queryBuilder->select('COUNT(fidelite.id)');

    return $queryBuilder->getQuery()->getSingleScalarResult();
}

public function countClientsByFidelity()
{
    $qb = $this->createQueryBuilder('f')
        ->select('f.type, COUNT(f) as count_clients')
        ->groupBy('f.type');

    $result = $qb->getQuery()->getResult();

    $counts = [];

    foreach ($result as $row) {
        $counts[$row['type']] = $row['count_clients'];
    }

    return $counts;
}
}
