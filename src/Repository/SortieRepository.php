<?php

namespace App\Repository;


use App\Entity\Sortie;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;



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
    private Security $security;
    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Sortie::class);
        $this->security = $security;
    }


    public function trierSortie30j()
    {
        $queryBuilder = $this->createQueryBuilder('s');

        $dateRange = new \DateTime('-30 days');

        $queryBuilder->where('s.startDate >= :dateRange');
        $queryBuilder->setParameter('dateRange', $dateRange);
        $queryBuilder->orderBy('s.startDate', 'DESC');
        $query = $queryBuilder->getQuery();
        return $query->getResult();

    }
    public function findByNameAndDate( $name,
                                       $filterDate,
                                       $filterDateMax,
                                       $checkBoxOrga,
                                       $checkBoxInscrit,
                                       $checkBoxNotInscrit,
                                       $sortiePasse,
                                       $campus

    )
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
        if ($checkBoxOrga) {
            $user = $this->security->getUser(); // Récupérer l'utilisateur connecté

            if ($user instanceof User) {
                $queryBuilder
                    ->innerJoin('s.organisateur', 'o') // Modifier "organizer" en "organisateur"
                    ->andWhere('o = :user')
                    ->setParameter('user', $user);
            }
        }
        if ($checkBoxInscrit) {
            $user = $this->security->getUser();

            if ($user instanceof User) {
                $queryBuilder
                    ->innerJoin('s.participant', 'p')
                    ->andWhere('p = :user')
                    ->setParameter('user', $user);
            }
        }
        if ($checkBoxNotInscrit) {
            $user = $this->security->getUser();

            if ($user instanceof User) {
                $subQuery = $this->createQueryBuilder('sub_s')
                    ->select('sub_s.id')
                    ->join('sub_s.participant', 'sub_p')
                    ->where('sub_p.id = :user_id')
                    ->getDQL();

                $queryBuilder
                    ->andWhere($queryBuilder->expr()->notIn('s.id', $subQuery))
                    ->setParameter('user_id', $user->getId());
            }
        }
        if ($sortiePasse) {
            $date = new \DateTime('now');
            $queryBuilder->andWhere('s.startDate < :date');
            $queryBuilder->setParameter('date', $date);

        }
        if ($campus) {
            $queryBuilder->andWhere('s.campus = :campus');
            $queryBuilder->setParameter('campus', $campus);
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
