<?php

namespace App\Repository;

use App\Entity\TailleBoissonCommande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TailleBoissonCommande>
 *
 * @method TailleBoissonCommande|null find($id, $lockMode = null, $lockVersion = null)
 * @method TailleBoissonCommande|null findOneBy(array $criteria, array $orderBy = null)
 * @method TailleBoissonCommande[]    findAll()
 * @method TailleBoissonCommande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TailleBoissonCommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TailleBoissonCommande::class);
    }

    public function add(TailleBoissonCommande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TailleBoissonCommande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return TailleBoissonCommande[] Returns an array of TailleBoissonCommande objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TailleBoissonCommande
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
