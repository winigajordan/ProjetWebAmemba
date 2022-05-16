<?php

namespace App\Controller;

use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AgendaController extends AbstractController
{
    #[Route('/agenda', name: 'app_agenda')]
    public function index(EvenementRepository $ripo): Response
    {
        return $this->render('agenda/index.html.twig', [
            'controller_name' => 'AgendaController',
            'evenements' => $ripo -> findAll()
        ]);
    }
}
