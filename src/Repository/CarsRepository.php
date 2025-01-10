<?php

namespace App\Repository;

use App\Entity\Cars;
use App\Entity\Photos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cars>
 */
class CarsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cars::class);
    }

    public function findBySearchCriteria(array $criteria): array
    {
        $qb = $this->createQueryBuilder('c'); // 'p' is the alias for Cars

        if (!empty($criteria['name'])) {
            $qb->andWhere('c.name LIKE :name')
                ->setParameter('name', '%' . $criteria['name'] . '%');
        }

        if (!empty($criteria['from'])) {
            $qb->andWhere('c.price > :from')
                ->setParameter('from',   $criteria['from']  );
        }
        if (!empty($criteria['till'])) {
            $qb->andWhere('c.price < :till')
                ->setParameter('till',   $criteria['till']  );
        }

        if (!empty($criteria['descr'])) {
            $qb->andWhere('c.description LIKE :name')
                ->setParameter('name', '%' . $criteria['descr'] . '%');
        }

        if (!empty($criteria['brand'])) {
            $qb->andWhere('c.brand = :brand')
                ->setParameter('brand', $criteria['brand']);
        }

        $qb->leftJoin('c.photos', 'p');
        $qb->addSelect('p.name');


        //dd($qb->getQuery()->getSQL());
        //dd($qb->getQuery()->getResult());
        return $qb->getQuery()->getResult();
    }
    //    /**
    //     * @return Cars[] Returns an array of Cars objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Cars
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
