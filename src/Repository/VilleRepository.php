<?php

namespace App\Repository;

use App\Entity\Ville;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ville>
 *
 * @method Ville|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ville|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ville[]    findAll()
 * @method Ville[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VilleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ville::class);
    }

    public function getLieuPerVille(int $villeId) {
        $queryBuilder = $this->createQueryBuilder('v');

        $queryBuilder->leftJoin('v.lieu', 'lieu')
            ->addSelect('lieu');

        $queryBuilder->where('v.id = :villeId')->setParameter('villeId', $villeId)
            ->OrderBy('lieu.nom', 'ASC');

        $query = $queryBuilder->getQuery();

        $result = $query->getResult();

        return $result;
    }
}
