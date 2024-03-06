<?php

namespace App\Repository;

use App\Entity\Campus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Campus>
 *
 * @method Campus|null find($id, $lockMode = null, $lockVersion = null)
 * @method Campus|null findOneBy(array $criteria, array $orderBy = null)
 * @method Campus[]    findAll()
 * @method Campus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CampusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Campus::class);
    }

    public function getCampusById(int $id): ?Campus
    {
        return $this->findOneBy(['campus_id' => $id]);
    }


    public function findByName($campus) {
        $queryBuilder = $this->createQueryBuilder('c');

        if ($campus) {
            $queryBuilder
                ->andWhere('c.name LIKE :campus')
                ->setParameter('campus', '%' . $campus . '%');
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
