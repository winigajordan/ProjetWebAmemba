<?php

namespace App\Controller;

use App\Repository\OffreEmploisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmploisDetailsController extends AbstractController
{

    public function __construct(
        OffreEmploisRepository $emploisRipo
    ){
        $this->emploisRipo = $emploisRipo;
    }

    #[Route('/emplois/details/{slug}', name: 'app_emplois_details')]
    public function index($slug): Response
    {
        return $this->render('emplois_details/index.html.twig', [
            'controller_name' => 'EmploisDetailsController',
            'emplois'=> $this->emploisRipo->findOneBy(['slug'=>$slug])
        ]);
    }
}
