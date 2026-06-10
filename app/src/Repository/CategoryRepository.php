<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }
    
     // Récupère toutes les catégories ET leurs types en une seule requête SQL
    // grâce au JOIN. Sans ce JOIN, Doctrine ferait une requête supplémentaire
    // pour chaque catégorie (problème N+1 — très coûteux en performance).
    public function findAllWithTypes(): array
    {
        return $this->createQueryBuilder('c')
        ->leftJoin('c.categoryTypes', 'ct') // JOIN sur la relation OneToMany
        ->addSelect('ct') // inclut les types dans le résultat
        ->orderBy('c.id', 'ASC')
        ->getQuery()
        ->getResult();
    }
}
