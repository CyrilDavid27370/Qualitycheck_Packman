<?php

namespace App\Service\Admin;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class CategoryHandler
{
  public function __construct(
    private CategoryRepository $categoryRepository,
    private EntityManagerInterface $em,
  )
  {
  }

  public function getAllCategories(): array
  {
    return $this->categoryRepository->findAllWithTypes();
  }

  public function getCategoryById(int $id): Category
  {
    $category = $this->categoryRepository->find($id);

    return $category;
  }

  public function save(Category $category):void
  {
    $this->em->persist($category);
    $this->em->flush();
  }

  public function delete(int $id): void
  {
    $category = $this->getCategoryById($id);

    // Empêche la suppression si des types sont associés
        if (!$category->getCategoryTypes()->isEmpty()) {
            throw new \LogicException(
                'Impossible de supprimer cette catégorie : elle contient des types associés.'
            );
        }

        $this->em->remove($category);
        $this->em->flush();
  }

}