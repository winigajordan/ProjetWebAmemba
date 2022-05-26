<?php

namespace App\Repository;

use App\Entity\CategorieEvenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategorieEvenement>
 *
 * @method CategorieEvenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieEvenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieEvenement[]    findAll()
 * @method CategorieEvenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieEvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieEvenement::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(CategorieEvenement $entity, bool $flush = true): void
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
    public function remove(CategorieEvenement $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return CategorieEvenement[] Returns an array of CategorieEvenement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategorieEvenement
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
