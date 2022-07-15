<?php

namespace App\Repository;

use App\Entity\PostesBureau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PostesBureau>
 *
 * @method PostesBureau|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostesBureau|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostesBureau[]    findAll()
 * @method PostesBureau[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostesBureauRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostesBureau::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(PostesBureau $entity, bool $flush = true): void
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
    public function remove(PostesBureau $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return PostesBureau[] Returns an array of PostesBureau objects
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
    public function findOneBySomeField($value): ?PostesBureau
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
