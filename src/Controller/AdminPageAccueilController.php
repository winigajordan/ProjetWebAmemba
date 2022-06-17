<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPageAccueilController extends AbstractController
{
    #[Route('/admin/page/accueil', name: 'app_admin_page_accueil')]
    public function index(): Response
    {
        return $this->render('admin/admin_page_accueil/index.html.twig', [
            'controller_name' => 'AdminPageAccueilController',
        ]);
    }
}
