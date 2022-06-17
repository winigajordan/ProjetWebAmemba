<?php

namespace App\Controller;

use DateTime;
use App\Repository\OffreEmploisRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmploisController extends AbstractController
{
    public function __construct(
        OffreEmploisRepository $offreRipo
    )
    {
        $this->offreRipo = $offreRipo;
    }

    #[Route('/emplois', name: 'app_emplois')]
    public function index(): Response
    {
        
        return $this->render('emplois/index.html.twig', [
            'emplois' => $this->jobs()
        ]);
    }

    public function jobs(){
        $today = new DateTime();
        $events =  $this->offreRipo->findBy(['etat'=>'VALIDE'],['createAt' => 'ASC']);
        $future = [];
        
        foreach ($events as $var) {
            if ($var -> getEndAt() > $today) {
                $future[] = $var;
            }
        }
        return $future;
    }
    #[Route('/emplois/recherche', name: 'app_emplois_search')]
    public function recherche(Request $request){
        dd($request->request);
    }

}
