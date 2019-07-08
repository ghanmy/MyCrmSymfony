<?php

namespace App\Repository;

use App\Entity\Activityarea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Activityarea|null find($id, $lockMode = null, $lockVersion = null)
 * @method Activityarea|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activityarea[]    findAll()
 * @method Activityarea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityareaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Activityarea::class);
    }

    // /**
    //  * @return Activityarea[] Returns an array of Activityarea objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Activityarea
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
