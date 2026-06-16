<?php

namespace App\Controller;

use App\Entity\Certificate;
use App\Form\CertificateFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class CertificateController extends AbstractController
{   
    public function __construct(
        private CertificateHandler $certificateHandler
    )
    {
    }

    // ── Création d'un certificat ───────────────────────────
    // Le categoryTypeId est passé en paramètre depuis le dashboard
    #[Route('/certificate/new/{categoryTypeId}', name: 'app_certificate_new', requirements: ['categoryTypeId' => '\d+'])]
    public function new(Request $request, int $categoryTypeId): Response
    {   
        $categoryType = $this->certificateHandler->getCategoryTypeById($categoryTypeId);
        $criterions = $this->certificateHandler->getCriterionsByCategoryType($categoryTypeId);

        $certificate = new Certificate();
        $certificate->setCategoryType($categoryType);
        $certificate->setUser($this->getUser());
        $certificate->setCreatedAt(new \DateTimeImmutable());

        $form = $this->createForm(CertificateFormType::class, $certificate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupère les évaluations saisies (SO/C/NC par critère)
            $evaluations = $request->request->all('evaluations');
            $this->certificateHandler->save($certificate, $evaluations);

            $this->addFlash('success', 'Certificat créé avec succès.');

            return $this->redirectToRoute('app-certificate_show', [
                'id' => $certificate->getId(),
            ]);

        }
        return $this->render('certificate/new.html.twig', [
            'form' => $form,
            'categoryType' => $categoryType,
            'criterions' =>$criterions,
        ]);
    }

    // ── Détail d'un certificat ─────────────────────────────
    #[Route('/certificate/{id}', name: 'app_certificate_show', requirements: ['id' => '\d+'])]
    public function show(int $id): Response
    {
        $certificate = $this->certificateHandler->getCertificateById($id);

        return $this->render('certificate/show.html.twig', [
            'certificate' => $certificate,
        ]);
    }
}
