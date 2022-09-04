<?php

namespace App\Repository;

use App\Entity\MenuCommandeTailleBoisson;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MenuCommandeTailleBoisson>
 *
 * @method MenuCommandeTailleBoisson|null find($id, $lockMode = null, $lockVersion = null)
 * @method MenuCommandeTailleBoisson|null findOneBy(array $criteria, array $orderBy = null)
 * @method MenuCommandeTailleBoisson[]    findAll()
 * @method MenuCommandeTailleBoisson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuCommandeTailleBoissonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MenuCommandeTailleBoisson::class);
    }

    public function add(MenuCommandeTailleBoisson $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MenuCommandeTailleBoisson $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return MenuCommandeTailleBoisson[] Returns an array of MenuCommandeTailleBoisson objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MenuCommandeTailleBoisson
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
