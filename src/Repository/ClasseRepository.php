<?php

namespace App\Repository;

use App\Entity\Classe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Classe>
 *
 * @method Classe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Classe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Classe[]    findAll()
 * @method Classe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClasseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Classe::class);
    }

//    /**
//     * @return Classe[] Returns an array of Classe objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Classe
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

function tri_asc()
{
    return $this->createQueryBuilder('evenement')
        ->orderBy('evenement.nom ','ASC')
        ->getQuery()->getResult();
}
function tri_desc()
{
    return $this->createQueryBuilder('evenement')
        ->orderBy('evenement.nom ','DESC')
        ->getQuery()->getResult();
}


   /**
    * @return Classe[] Returns an array of Classe objects
    */
   public function findclasseswithyear($year,$value): array
   {
       return $this->createQueryBuilder('c')
           ->andWhere('c.filiere = :val and c.year = :year ')
           ->setParameter('val', $value)
           ->setParameter('year', $year)
           ->getQuery()
           ->getResult()
       ;
   }




}
