<?php

namespace App\Controller;

use App\Service\DashboardHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{   
    public function __construct(
        private DashboardHandler $dashboardHandler
        )
    {
    }
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {   
        $categories = $this->dashboardHandler->getCategories();

        return $this->render('dashboard/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/dashboard/categorie/{id}', name: 'app_dashboard_category')]
    public function byCategory(int $id):Response
    {
        // Le service gère la récupération ET lance une exception si introuvable
        $selectedType = $this->dashboardHandler->getCategoryTypeById($id);
        $categories = $this->dashboardHandler->getCategories();

        return $this->render('dashboard/index.html.twig', [
            'categories' => $categories,
            'selectedType' => $selectedType,
        ]);
    }
}
