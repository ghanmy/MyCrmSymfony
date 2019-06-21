<?php

namespace App\Repository;

use App\Entity\Prospect;
use App\Entity\ProspectSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Prospect|null find($id, $lockMode = null, $lockVersion = null)
 * @method Prospect|null findOneBy(array $criteria, array $orderBy = null)
 * @method Prospect[]    findAll()
 * @method Prospect[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProspectsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Prospect::class);
    }

    // /**
    //  * @return Prospect[] Returns an array of Prospects objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Prospects
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    // /**
    //  * @return Prospect[] Returns an array of Prospects objects
    //  */

    public function findByUser($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    public function findAllQuery(ProspectSearch $search): Query
    {
        $query = $this->createQueryBuilder('u');
        if($search->getName())
            $query = $query->andWhere('u.name LIKE :prospectname')
                ->setParameter('prospectname', '%'.$search->getName().'%');
        if($search->getType())
            $query = $query->andWhere('u.type LIKE :prospecttype')
                ->setParameter('prospecttype', '%'.$search->getType().'%');
        if($search->getEmail())
            $query = $query->andWhere('u.email LIKE :prospectemail')
                ->setParameter('prospectemail', '%'.$search->getEmail().'%');

        return $query->getQuery();
    }

}
