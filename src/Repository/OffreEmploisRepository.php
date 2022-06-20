<?php

namespace App\Repository;

use App\Entity\OffreEmplois;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OffreEmplois>
 *
 * @method OffreEmplois|null find($id, $lockMode = null, $lockVersion = null)
 * @method OffreEmplois|null findOneBy(array $criteria, array $orderBy = null)
 * @method OffreEmplois[]    findAll()
 * @method OffreEmplois[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreEmploisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OffreEmplois::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(OffreEmplois $entity, bool $flush = true): void
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
    public function remove(OffreEmplois $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return OffreEmplois[] Returns an array of OffreEmplois objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OffreEmplois
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
