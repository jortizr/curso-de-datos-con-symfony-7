<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }
    
    public function findLatest($tag): array{// //dd($query->getResult());
        $qb = $this->createQueryBuilder('product')
                ->addSelect('comments', 'tags')
                ->leftJoin('product.comments', 'comments') //union de tablas, devuelve todas las finas cuando hay coincidencias en ambas tablas, pero excluye las que no coinciden
                ->leftJoin('product.tags', 'tags')
                ->orderBy('product.id', 'DESC');
        //anidamos la consulta para respecto a las etiquetas
        if($tag){
            $qb->setParameter('tag', $tag)->andWhere(':tag MEMBER OF product.tags');
        }


        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
