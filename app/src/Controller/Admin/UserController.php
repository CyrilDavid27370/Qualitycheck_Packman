<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Admin\UserFormType;
use App\Service\Admin\UserHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
final class UserController extends AbstractController
{   
    public function __construct(
        private UserHandler $userHandler
    )
    {
    }

    // ── Liste ──────────────────────────────────────────────
    #[Route('/admin/user', name: 'app_admin_user_index')]
    public function index(): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $this->userHandler->getAllUsers(),
        ]);
    }

    // ── Détail ─────────────────────────────────────────────

    #[Route('/admin/user/{id}', name: 'app_admin_user_show', requirements: ['id' => '\d+'])]
    public function show(int $id): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $this->userHandler->getUserById($id),
        ]);
    }

    // ── Création et modification ───────────────────────────

    #[Route('/admin/user/save/{id}', name: 'app_admin_user_save', requirements: ['id' => '\d+'], defaults: ['id' => null])]
    public function save(Request $request, ?int $id = null): Response
    {
        $user = $id ? $this->userHandler->getUserById($id) : new User();
        $isEdit = $id !== null;

        $form = $this->createForm(UserFormType::class, $user, ['is_edit' => $isEdit,]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userHandler->save($user, $form, $isEdit);

            $this->addFlash('success', $isEdit ? 'Utilisateur modifié avec succès.' : 'Utilisateur crée avec succès.');

            return $this->redirectToRoute('app_admin_user_index');
        }

        return $this->render('admin/user/save.html.twig', [
            'form' => $form,
            'isEdit' => $isEdit,
            'user' => $user,
        ]);
    }

    // ── Suppression ────────────────────────────────────────

    #[Route('/admin/user/delete/{id}', name: 'app_admin_user_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(int $id, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete_user_' . $id, $request->request->get('_token'))) {
            try {
                $this->userHandler->delete($id, $this->getUser());
                $this->addFlash('success', 'Utilisateur supprimé avec succès.');   
            } catch (\LogicException $e){
                $this->addFlash('warning', $e->getMessage());
            }
        
        }

        return $this->redirectToRoute('app_admin_user_index');
    }
}
