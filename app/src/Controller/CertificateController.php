<?php

namespace App\Controller;

use App\Entity\Certificate;
use App\Form\CertificateFormType;
use App\Service\CertificateHandler;
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
    
    // ══════════════════════════════════════════════════════
    // ESPACE ANALYSTE
    // ══════════════════════════════════════════════════════

    // ── Création d'un certificat ───────────────────────────
    // Le categoryTypeId est passé en paramètre depuis le dashboard
    #[Route('/certificate/new/{categoryTypeId}', name: 'app_certificate_new', requirements: ['categoryTypeId' => '\d+'])]
    public function new(Request $request, int $categoryTypeId): Response
    {   
        $categoryType = $this->certificateHandler->getCategoryTypeById($categoryTypeId);
        $criterions = $this->certificateHandler->getCriterionsByCategoryType($categoryType);

        $certificate = new Certificate();
        $certificate->setCategoryType($categoryType);
        $certificate->setUser($this->getUser());
        $certificate->setCreatedAt(new \DateTimeImmutable());

        $form = $this->createForm(CertificateFormType::class, $certificate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupère les évaluations saisies (SO/C/NC par critère)
            $evaluations = $request->request->all('evaluations');
            $ncPhotos = $request->files->all('nc_photos');

            $this->certificateHandler->save($certificate, $evaluations, $ncPhotos);

            $this->addFlash('success', 'Certificat créé avec succès.');

            return $this->redirectToRoute('app_certificate_show', [
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

    // ══════════════════════════════════════════════════════
    // ESPACE ADMIN
    // ══════════════════════════════════════════════════════

    // ── Liste admin de tous les certificats ────────────────
    #[Route('/admin/certificate', name: 'app_admin_certificate_index')]
    #[IsGranted('ROLE_ADMIN')]
    public function adminIndex(): Response
    {
        return $this->render('admin/certificate/index.html.twig', [
            'certificates' => $this->certificateHandler->getAllCertificates(),
        ]);
    }
    
    // ── Modification d'un certificat ───────────────────────
    #[Route('/admin/certificate/save/{id}', name: 'app_admin_certificate_save', requirements: ['id' => '\d+'], defaults: ['id' => null])]
    #[IsGranted('ROLE_ADMIN')]
    public function adminSave(Request $request, ?int $id = null): Response
    {
        $certificate = $this->certificateHandler->getCertificateById($id);
        $categoryType = $certificate->getCategoryType();
        $criterions = $this->certificateHandler->getCriterionsByCategoryType($categoryType);

        $form = $this->createForm(CertificateFormType::class, $certificate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $evaluations = $request->request->all('evaluations');
            $ncPhotos = $request->files->all('nc_photos');

            $this->certificateHandler->update($certificate, $evaluations, $ncPhotos);

            $this->addFlash('success', 'Certificat modifié avec succès.');

            return $this->redirectToRoute('app_admin_certificate_index');
        }
    
    
        return $this->render('certificate/new.html.twig', [
            'form' => $form,
            'categoryType' => $categoryType,
            'criterions' => $criterions,
            'isEdit' => true,
            'certificate' => $certificate,
        ]);
    }

     // ── Suppression d'un certificat ────────────────────────
    #[Route('/admin/certificate/delete/{id}', name: 'app_admin_certificate_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function adminDelete(int $id, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete_certificate_' . $id, $request->request->get('_token'))) {
            $this->certificateHandler->delete($id);
            $this->addFlash('success', 'Certificat supprimé avec succès.');
        }

        return $this->redirectToRoute('app_admin_certificate_index');
    }
}
