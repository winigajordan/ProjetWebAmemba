<?php

namespace App\Controller;

use App\Repository\DemandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DemandeController extends AbstractController
{
    #[Route('/admin/demande', name: 'demande')]
    public function index(DemandeRepository $dmd): Response
    {

        $demandes = $dmd -> findAll();
        return $this->render('./demande/index.html.twig', [
            'controller_name' => 'DemandeController',
            'demandes' => $demandes
        ]);
    }

    #[Route('/admin/demande/details/{id}', name: 'details_demande')]
    public function details($id, DemandeRepository $dmd) : Response
    {
        $demandes = $dmd -> findAll();
        $selected = $dmd -> find($id);
        return $this->render('./demande/index.html.twig',[
            'controller_name' => 'DemandeController',
            'demandes' => $demandes,
            'selected' => $selected
        ]);
    }

}
