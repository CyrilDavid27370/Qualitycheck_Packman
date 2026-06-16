<?php

namespace App\Service;

use App\Entity\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\CategoryTypeRepository;
use App\Repository\CertificateRepository;

// Ce service centralise toute la logique métier du dashboard.
// Le contrôleur ne fait qu'appeler ces méthodes et passer les données à Twig.
class DashboardHandler
{
    public function __construct(
        private  CategoryRepository     $categoryRepository,
        private  CategoryTypeRepository $categoryTypeRepository,
        private CertificateRepository $certificateRepository,
    ) {}

    // Récupère toutes les catégories avec leurs types associés.
    // On utilise une requête optimisée avec JOIN pour éviter
    // le problème N+1 (une requête SQL par catégorie).
    public function getCategories(): array
    {
        return $this->categoryRepository->findAllWithTypes();
    }

    // Récupère un CategoryType par son id.
    // Lance une NotFoundHttpException si introuvable —
    // Symfony la convertit automatiquement en réponse HTTP 404.
    public function getCategoryTypeById(int $id): CategoryType
    {
        $type = $this->categoryTypeRepository->find($id);

        return $type;
    }

    public function getCertificatesByCategoryType(CategoryType $type): array
    {
        return $this->certificateRepository->findBy(
            ['categoryType' => $type],
            ['createdAt' => 'DESC']
        );
    }
}