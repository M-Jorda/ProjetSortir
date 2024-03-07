<?php

namespace App\Repository;

use App\Entity\GroupStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GroupStatus>
 *
 * @method GroupStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupStatus[]    findAll()
 * @method GroupStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupStatus::class);
    }

    //    /**
    //     * @return GroupStatus[] Returns an array of GroupStatus objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?GroupStatus
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
