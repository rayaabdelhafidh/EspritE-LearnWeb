<?php

namespace App\Repository;

use App\Entity\Emploi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Emploi>
 *
 * @method Emploi|null find($id, $lockMode = null, $lockVersion = null)
 * @method Emploi|null findOneBy(array $criteria, array $orderBy = null)
 * @method Emploi[]    findAll()
 * @method Emploi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmploiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emploi::class);
    }

//    /**
//     * @return Emploi[] Returns an array of Emploi objects
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

//    public function findOneBySomeField($value): ?Emploi
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function findUniqueEmploiDates(): array
{
    return $this->createQueryBuilder('e')
        ->select('e.premierdate, e.dernierdate')
        ->groupBy('e.premierdate, e.dernierdate')
        ->getQuery()
        ->getResult();
}
}
