<?php

namespace App\Repository;

use App\Entity\PortionFriteCommande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PortionFriteCommande>
 *
 * @method PortionFriteCommande|null find($id, $lockMode = null, $lockVersion = null)
 * @method PortionFriteCommande|null findOneBy(array $criteria, array $orderBy = null)
 * @method PortionFriteCommande[]    findAll()
 * @method PortionFriteCommande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PortionFriteCommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PortionFriteCommande::class);
    }

    public function add(PortionFriteCommande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PortionFriteCommande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return PortionFriteCommande[] Returns an array of PortionFriteCommande objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PortionFriteCommande
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
