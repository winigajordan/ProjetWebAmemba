<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserCreationController extends AbstractController
{
    #[Route('/admin/user/creation', name: 'app_admin_user_creation'), IsGranted('ROLE_MEMBRE')]
    public function index(): Response
    {
        return $this->render('admin/admin_user_creation/index.html.twig', [
            'controller_name' => 'AdminUserCreationController',
        ]);
    }
}
