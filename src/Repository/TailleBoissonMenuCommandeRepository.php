<?php

namespace App\Repository;

use App\Entity\TailleBoissonMenuCommande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TailleBoissonMenuCommande>
 *
 * @method TailleBoissonMenuCommande|null find($id, $lockMode = null, $lockVersion = null)
 * @method TailleBoissonMenuCommande|null findOneBy(array $criteria, array $orderBy = null)
 * @method TailleBoissonMenuCommande[]    findAll()
 * @method TailleBoissonMenuCommande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TailleBoissonMenuCommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TailleBoissonMenuCommande::class);
    }

    public function add(TailleBoissonMenuCommande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TailleBoissonMenuCommande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return TailleBoissonMenuCommande[] Returns an array of TailleBoissonMenuCommande objects
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

//    public function findOneBySomeField($value): ?TailleBoissonMenuCommande
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
