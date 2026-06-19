<?php

namespace App\Service\Admin;

use App\Entity\Category;
use App\Entity\CategoryType;
use App\Entity\Criterion;
use App\Repository\CategoryRepository;
use App\Repository\CategoryTypeRepository;
use App\Repository\CriterionRepository;
use Doctrine\ORM\EntityManagerInterface;

class CategoryHandler
{
  public function __construct(
    private CategoryRepository $categoryRepository,
    private CategoryTypeRepository $categoryTypeRepository,
    private CriterionRepository $criterionRepository,
    private EntityManagerInterface $em,
  )
  {
  }

   // ── Category  ───────────────────────────

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

   // ── CategoryType  ───────────────────────────
  
  public function getCategoryTypeById(int $id): CategoryType
  {
    $type = $this->categoryTypeRepository->find($id);

    return $type;
  }

  public function saveCategoryType(CategoryType $type): void
  {
    $this->em->persist($type);
    $this->em->flush();
  }

  public function deleteCategoryType(int $id): void
  {
    $type = $this->getCategoryTypeById($id);

    // Empêche la suppression si des critères sont associés
        if (!$type->getCriteria()->isEmpty()) {
            throw new \LogicException(
                'Impossible de supprimer ce type : il contient des critères associés.'
            );
        }

        $this->em->remove($type);
        $this->em->flush();
  }

   // ── Critères ───────────────────────────────────────────
  
  public function getCriterionById(int $id): Criterion
  {
    $criterion = $this->criterionRepository->find($id);

    return $criterion;
  }

  public function saveCriterion(Criterion $criterion): void
  {
    $this->em->persist($criterion);
    $this->em->flush();
  }

  public function deleteCriterion(int $id): void
  {
    $criterion =$this->getCriterionById($id);

    if (!$criterion->getEvaluations() ->isEmpty()) {
      throw new \LogicException(
        'Impossible de supprimer ce critère : il est utilisé dans des certificats.'
      );
    }

    $this->em->remove($criterion);
    $this->em->flush();
  }

  public function getCriterionsByCategoryType(int $categoryTypeId): array
  {
      return $this->criterionRepository->findBy(
        ['categoryType' => $categoryTypeId],
        ['itemNumber' => 'ASC']
    );
  }

}