<?php

namespace App\Service;

use App\Entity\CategoryType;
use App\Entity\Certificate;
use App\Entity\Evaluation;
use App\Repository\CategoryTypeRepository;
use App\Repository\CertificateRepository;
use App\Repository\CriterionRepository;
use Doctrine\ORM\EntityManagerInterface;

class CertificateHandler
{
  public function __construct(
    private CertificateRepository $certificateRepository,
    private CategoryTypeRepository $categoryTypeRepository,
    private CriterionRepository $criterionRepository,
    private EntityManagerInterface $em
  )
  {
  }

  public function getCategoryTypeById(int $id): CategoryType
  {
    $type = $this->categoryTypeRepository->find($id);

    return $type;
  }

  public function getCriterionsByCategoryType(CategoryType $type): array 
  {
    return $this->criterionRepository->findBy(
      ['categoryType' => $type],
      ['itemNumber' => 'ASC']
    );
  }

  public function getCertificateById(int $id): Certificate
  {
    $certificate =$this->certificateRepository->find($id);

    return $certificate;
  }

  public function getCertificatesByCategoryType(CategoryType $type): array 
  {
    return $this->certificateRepository->findBy(
      ['categoryType' => $type],
      ['createdAt' => 'DESC']
    );
  }

  // Sauvegarde le certificat + crée toutes les évaluations SO/C/NC
  public function save(Certificate $certificate, array $evaluations): void
  {
    $this->em->persist($certificate);

    // Pour chaque critère — crée une évaluation avec la cotation choisie
    foreach ($evaluations as $criterionId => $rating) {
      $criterion = $this->criterionRepository->find($criterionId);

      if (!$criterion) {
        continue;
      }

      $evaluation = new Evaluation();
      $evaluation->setCertificate($certificate);
      $evaluation->setCriterion($criterion);
      $evaluation->setRating($rating);
      $this->em->persist($evaluation);
    }
    
    $this->em->flush();
  }
}