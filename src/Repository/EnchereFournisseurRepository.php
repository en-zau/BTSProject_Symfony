<?php

namespace App\Repository;

use App\Entity\EnchereFournisseur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EnchereFournisseur|null find($id, $lockMode = null, $lockVersion = null)
 * @method EnchereFournisseur|null findOneBy(array $criteria, array $orderBy = null)
 * @method EnchereFournisseur[]    findAll()
 * @method EnchereFournisseur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnchereFournisseurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EnchereFournisseur::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(EnchereFournisseur $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(EnchereFournisseur $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return EnchereFournisseur[] Returns an array of EnchereFournisseur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EnchereFournisseur
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
