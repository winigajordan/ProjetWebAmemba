<?php

namespace App\Controller;

use App\Repository\OffreEmploisRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmploisDetailsController extends AbstractController
{

    public function __construct(
        OffreEmploisRepository $emploisRipo
    ){
        $this->emploisRipo = $emploisRipo;
    }

    #[Route('/emplois/details/{slug}', name: 'app_emplois_details'), IsGranted("ROLE_MEMBRE")]
    public function index($slug): Response
    {
        $selected = $this->emploisRipo->findOneBy(['slug'=>$slug]);
        if($selected == null){
            return $this->redirectToRoute('app_emplois');
        } else {
            return $this->render('emplois_details/index.html.twig', [
            'controller_name' => 'EmploisDetailsController',
            'emplois'=> $selected,
            ]);
        }
        
    }
}
