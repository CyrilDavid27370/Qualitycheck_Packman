<?php

namespace App\Service;

use App\Entity\CategoryType;
use App\Entity\Certificate;
use App\Entity\Evaluation;
use App\Entity\Image;
use App\Repository\CategoryTypeRepository;
use App\Repository\CertificateRepository;
use App\Repository\CriterionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CertificateHandler
{
    public function __construct(
        private CertificateRepository  $certificateRepository,
        private CategoryTypeRepository $categoryTypeRepository,
        private CriterionRepository    $criterionRepository,
        private EntityManagerInterface $em,
        private string                 $uploadNcPhotosDir
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

    public function getAllCertificates(): array
    {
        return $this->certificateRepository->findBy(
            [],
            ['createdAt' => 'DESC']
        );
    }

    // ── Création ───────────────────────────────────────────
    public function save(Certificate $certificate, array $evaluations, array $ncPhotos = []): void
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

            // Upload des photos si NC
            if ($rating === 'NC' && isset($ncPhotos[$criterionId])) {
                $this->savePhotos($evaluation, $ncPhotos[$criterionId], $criterion->getItemNumber());
            }
        }

        $this->em->flush();
    }

    // ── Modification — supprime et recrée les évaluations ──
    public function update(Certificate $certificate, array $evaluations, array $ncPhotos = []): void
    {
        foreach ($certificate->getEvaluations() as $evaluation) {
            $this->em->remove($evaluation);
        }

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

            // Upload des photos si NC
            if ($rating === 'NC' && isset($ncPhotos[$criterionId])) {
                $this->savePhotos($evaluation, $ncPhotos[$criterionId], $criterion->getItemNumber());
            }
        }

        $this->em->persist($certificate);
        $this->em->flush();
    }

    // ── Upload et sauvegarde des photos NC ─────────────────
    private function savePhotos(Evaluation $evaluation, array $files, int $itemNumber): void
    {
        $count = 0;

        foreach ($files as $file) {
            if (!$file instanceof UploadedFile || $count >= 20) {
                break;
            }

            // Vérifie le type MIME réel du fichier
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array($file->getMimeType(), $allowedMimes)) {
                continue;
            }

            // Génère un nom unique pour le fichier
            $filename = bin2hex(random_bytes(16)) . '.' . $file->guessExtension();

            // Déplace le fichier dans le dossier uploads
            $file->move($this->uploadNcPhotosDir, $filename);

            // Crée l'entité Image
            $image = new Image();
            $image->setPath('uploads/nc-photos/' . $filename);
            $image->setAlt('Photo NC critère n°' . $itemNumber);
            $image->setCreatedAt(new \DateTimeImmutable());
            $image->setEvaluation($evaluation);
            $this->em->persist($image);

            $count++;
        }
    }

    // ── Suppression ────────────────────────────────────────
    public function delete(int $id): void
    {
        $certificate = $this->getCertificateById($id);
        $this->em->remove($certificate);
        $this->em->flush();
    }
}