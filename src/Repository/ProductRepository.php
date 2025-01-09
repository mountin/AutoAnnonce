<?php
/**
 * Created by PhpStorm.
 * User: mountin
 * Date: 1/9/25
 * Time: 9:53 AM
 */

namespace App\Repository;

use App\Entity\Cars;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cars::class);
    }

    public function findBySearchCriteria(array $criteria): array
    {
        $qb = $this->createQueryBuilder('p'); // 'p' is the alias for Cars

        if (!empty($criteria['name'])) {
            $qb->andWhere('p.name LIKE :name')
                ->setParameter('name', '%' . $criteria['name'] . '%');
        }

        if (!empty($criteria['price'])) {
            $qb->andWhere('p.price > :price')
                ->setParameter('price',   $criteria['price']  );
        }

        if (!empty($criteria['descr'])) {
            $qb->andWhere('p.description LIKE :name')
                ->setParameter('name', '%' . $criteria['descr'] . '%');
        }

        if (!empty($criteria['brand'])) {
            $qb->join('p.brands', 'c') // Assuming a ManyToMany relationship with brands
            ->andWhere('c.brand_id = :brands')
                ->setParameter('brand_id', '%' . $criteria['brand'] . '%');
        }

        return $qb->getQuery()->getResult();
    }
}