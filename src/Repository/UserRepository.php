<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function findOneByEmail($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findAllQuery(UserSearch $search): Query
    {
        $query = $this->createQueryBuilder('u');
        if($search->getName())
        $query = $query->andWhere('u.nom LIKE :username')
                ->setParameter('username', '%'.$search->getName().'%');
        if($search->getPrenom())
            $query = $query->andWhere('u.prenom LIKE :userprenom')
                ->setParameter('userprenom', '%'.$search->getPrenom().'%');
        if($search->getEmail())
            $query = $query->andWhere('u.email LIKE :useremail')
                ->setParameter('useremail', '%'.$search->getEmail().'%');
            
        return $query->getQuery();
                       
    }

    public function findByRole($value)
    {
       $role= serialize(array($value));
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles = :val')
            ->setParameter('val',$role)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByRoleIfActive($value)
    {
        $role= serialize(array($value));
        return $this->createQueryBuilder('u')
            ->where('u.isActive = 1')
            ->andWhere('u.roles = :val')
            ->setParameter('val',$role)
            ->getQuery()
            ->getResult()
            ;
    }
}
