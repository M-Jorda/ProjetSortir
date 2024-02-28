<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }


    public function findByNameAndDate($name, $filterDate, $filterDateMax)
    {
        $queryBuilder = $this->createQueryBuilder('s');
        // Filtrer par nom (optionnel)
        if ($name) {
            $queryBuilder->andWhere('s.name LIKE :name');
            $queryBuilder->setParameter('name', '%' . $name . '%');
        }

        // Filtrer par date (si fournie)
        if ($filterDate) {
            $queryBuilder->andWhere('s.startDate >= :filterDate');
            $queryBuilder->setParameter('filterDate', $filterDate);
        }
        if ($filterDateMax) {
            $queryBuilder->andWhere('s.startDate <= :filterDateMax');
            $queryBuilder->setParameter('filterDateMax', $filterDateMax);
        }

        $query = $queryBuilder->getQuery();
        $results = $query->getResult();
        return $results;
    }




//    /**
//     * @return Sortie[] Returns an array of Sortie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sortie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
