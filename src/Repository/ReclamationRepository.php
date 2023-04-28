<?php

namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reclamation>
 *
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

    public function save(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    // recherche
    public function searchByTerm($term)
    {
        $qb = $this->createQueryBuilder('r');
        $qb->where(
            $qb->expr()->orX(
                $qb->expr()->like('r.message', ':term'),
                $qb->expr()->like('r.feedback', ':term'),
                $qb->expr()->like('r.idReclamation', ':term')                
            )
        );
        $qb->setParameter('term', '%'.$term.'%');
    
        return $qb->getQuery()->getResult();
    }
    // tri
    public function findAllOrderedByFeedback()
{
    $qb = $this->createQueryBuilder('r');
    $qb->orderBy('r.feedback', 'ASC');
    return $qb->getQuery()->getResult();
}
}