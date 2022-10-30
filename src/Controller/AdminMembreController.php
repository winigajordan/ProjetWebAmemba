<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminMembreController extends AbstractController
{
    #[Route('/admin/membre', name: 'app_admin_membre')]
    public function index(): Response
    {
        return $this->render('admin_membre/index.html.twig', [
            'controller_name' => 'AdminMembreController',
        ]);
    }
}
