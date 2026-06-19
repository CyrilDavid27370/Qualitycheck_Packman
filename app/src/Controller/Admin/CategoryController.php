<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\CategoryType;
use App\Entity\Criterion;
use App\Service\Admin\CategoryHandler;
use App\Form\Admin\CategoryFormType;
use App\Form\Admin\CategoryTypeFormType;
use App\Form\Admin\CriterionFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
final class CategoryController extends AbstractController
{   
    public function __construct(
        private CategoryHandler $categoryHandler
    )
    {
    }

    // ── Liste ──────────────────────────────────────────────
    #[Route('/admin/category', name: 'app_admin_category_index')]
    public function index(): Response
    {
        return $this->render('admin/category/index.html.twig', [
            'categories' => $this->categoryHandler->getAllCategories(),
        ]);
    }

     // ── Création et modification Category ───────────────────────────
    #[Route('/admin/category/save/{id}', name: 'app_admin_category_save', requirements: ['id' => '\d+'], defaults: ['id' => null])]
    public function save (Request $request, ?int $id = null):Response
    {
        $category = $id ? $this->categoryHandler->getCategoryById($id) : new Category();
        $isEdit = $id !== null;

        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryHandler->save($category);

            $this->addFlash(
                'success',
                $isEdit ? 'Catégorie modifiéé avec succès.' : 'Categorie ajoutée avec succès.'
            );

            return $this->redirectToRoute('app_admin_category_index');
        }

        return $this->render('admin/category/save.html.twig', [
            'form' => $form,
            'isEdit' => $isEdit,
        ]);
    }

    // ── Suppression Category ────────────────────────────────────────
    #[Route('/admin/category/delete/{id}', name: 'app_admin_category_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(int $id, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete_category_' . $id, $request->request->get('_token'))) {

                $this->categoryHandler->delete($id);
                $this->addFlash('success', 'Categorie supprimée avec succès.');
            }

            return $this->redirectToRoute('app_admin_category_index');
    }

    // ── Création et modification CategoryType  ───────────────────────────
    #[Route('/admin/categoryType/save/{id}', name: 'app_admin_categoryType_save', requirements: ['id' => '\d+'], defaults: ['id' => null])]
    public function saveCategoryType (Request $request, ?int $id = null):Response
    {
        $type = $id ? $this->categoryHandler->getCategoryTypeById($id) : new CategoryType();
        $isEdit = $id !== null;

        $form = $this->createForm(CategoryTypeFormType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryHandler->saveCategoryType($type);

            $this->addFlash(
                'success',
                $isEdit ? 'Type modifié avec succès.' : 'Type ajouté avec succès.'
            );

            return $this->redirectToRoute('app_admin_category_index');
        }

        return $this->render('admin/categoryType/save_type.html.twig', [
            'form' => $form,
            'isEdit' => $isEdit,
        ]);
    }

    // ── Suppression Category ────────────────────────────────────────
    #[Route('/admin/categoryType/delete/{id}', name: 'app_admin_categoryType_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function deleteCategoryType(int $id, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete_category_' . $id, $request->request->get('_token'))) {

                $this->categoryHandler->deleteCategoryType($id);
                $this->addFlash('success', 'Type supprimé avec succès.');
            }

            return $this->redirectToRoute('app_admin_category_index');
    }

    // ── Critères ───────────────────────────────────────────
    // ── Liste des critères d'un CategoryType ───────────────
    #[Route('/admin/criterion/{categoryTypeId}', name: 'app_admin_criterion_index', requirements: ['categoryTypeId' => '\d+'])]
    public function criterionIndex(int $categoryTypeId): Response
    {
        $categoryType = $this->categoryHandler->getCategoryTypeById($categoryTypeId);
        $criterions   = $this->categoryHandler->getCriterionsByCategoryType($categoryTypeId);

        return $this->render('admin/criterion/index.html.twig', [
            'categoryType' => $categoryType,
            'criterions'   => $criterions,
        ]);
    }
    // ── Création et modification Criterion ─────────────────
    #[Route('/admin/criterion/save/{categoryTypeId}/{id}', name: 'app_admin_criterion_save', requirements: ['categoryTypeId' => '\d+'], defaults: ['id' => null])]
    public function saveCriterion (Request $request, int $categoryTypeId, ?int $id = null):Response
    {
        $categoryType = $this->categoryHandler->getCategoryTypeById($categoryTypeId);
        $criterion = $id ? $this->categoryHandler->getCriterionById($id): new Criterion;
        $isEdit = $id !== null;

        if (!$isEdit) {
            $criterion->setCategoryType($categoryType);
        }

        $form = $this->createForm(CriterionFormType::class, $criterion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryHandler->saveCriterion($criterion);

            $this->addFlash(
                'success',
                $isEdit ? 'Critère modifié avec succès.' : 'Critère ajouté avec succès.'
            );

            return $this->redirectToRoute('app_admin_category_index');
        }

        return $this->render('admin/criterion/save.html.twig', [
            'form' => $form,
            'isEdit' => $isEdit,
            'categoryType' => $categoryType,
        ]);
    }

    // ── Suppression Criterion ──────────────────────────────
    #[Route('/admin/criterion/delete/{id}', name: 'app_admin_criterion_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function deleteCriterion(int $id, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete_criterion_' . $id, $request->request->get('_token'))) {
            $this->categoryHandler->deleteCriterion($id);
            $this->addFlash('success', 'Critere supprimé avec succès.');

        }

        return $this->redirectToRoute('app_admin_category_index');
    }
}

