<?php

namespace App\Service;

use App\Entity\CategoryType;
use App\Entity\Certificate;
use App\Entity\Evaluation;
use App\Repository\CategoryTypeRepository;
use App\Repository\CertificateRepository;
use App\Repository\CriterionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CertificateHandler
{
    public function __construct(
        private CertificateRepository  $certificateRepository,
        private CategoryTypeRepository $categoryTypeRepository,
        private CriterionRepository    $criterionRepository,
        private EntityManagerInterface $em
    ) {}

    public function getCategoryTypeById(int $id): CategoryType
    {
        $type = $this->categoryTypeRepository->find($id);

        if (!$type) {
            throw new NotFoundHttpException(
                sprintf('Type #%d introuvable.', $id)
            );
        }

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
        $certificate = $this->certificateRepository->find($id);

        if (!$certificate) {
            throw new NotFoundHttpException(
                sprintf('Certificat #%d introuvable.', $id)
            );
        }

        return $certificate;
    }

    public function getCertificatesByCategoryType(CategoryType $type): array
    {
        return $this->certificateRepository->findBy(
            ['categoryType' => $type],
            ['createdAt' => 'DESC']
        );
    }

    // ── Récupère tous les certificats — pour la liste admin ─
    public function getAllCertificates(): array
    {
        return $this->certificateRepository->findBy(
            [],
            ['createdAt' => 'DESC']
        );
    }

    // ── Création ───────────────────────────────────────────
    public function save(Certificate $certificate, array $evaluations): void
    {
        $this->em->persist($certificate);

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

    // ── Modification — supprime et recrée les évaluations ──
    public function update(Certificate $certificate, array $evaluations): void
    {
        // Supprime les anciennes évaluations
        foreach ($certificate->getEvaluations() as $evaluation) {
            $this->em->remove($evaluation);
        }

        // Recrée avec les nouvelles cotations
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

        $this->em->persist($certificate);
        $this->em->flush();
    }

    // ── Suppression ────────────────────────────────────────
    public function delete(int $id): void
    {
        $certificate = $this->getCertificateById($id);
        $this->em->remove($certificate);
        $this->em->flush();
    }
}