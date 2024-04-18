<?php

namespace App\Repository;

use App\Entity\EmploiMatiere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EmploiMatiere>
 *
 * @method EmploiMatiere|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmploiMatiere|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmploiMatiere[]    findAll()
 * @method EmploiMatiere[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmploiMatiereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmploiMatiere::class);
    }

//    /**
//     * @return EmploiMatiere[] Returns an array of EmploiMatiere objects
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

//    public function findOneBySomeField($value): ?EmploiMatiere
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
