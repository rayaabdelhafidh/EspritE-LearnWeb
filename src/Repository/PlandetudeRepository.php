<?php

namespace App\Repository;

use App\Entity\Plandetude;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Plandetude>
 *
 * @method Plandetude|null find($id, $lockMode = null, $lockVersion = null)
 * @method Plandetude|null findOneBy(array $criteria, array $orderBy = null)
 * @method Plandetude[]    findAll()
 * @method Plandetude[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlandetudeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plandetude::class);
    }

//    /**
//     * @return Plandetude[] Returns an array of Plandetude objects
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

//    public function findOneBySomeField($value): ?Plandetude
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
