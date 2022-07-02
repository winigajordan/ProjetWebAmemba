<?php

namespace App\Controller;

use DateTime;
use App\Repository\OffreEmploisRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmploisController extends AbstractController
{
    public function __construct(
        OffreEmploisRepository $offreRipo
    )
    {
        $this->offreRipo = $offreRipo;
    }

    #[Route('/emplois', name: 'app_emplois'), IsGranted("ROLE_MEMBRE")]
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


    #[Route('/emplois/recherche', name: 'app_emplois_search'), IsGranted("ROLE_MEMBRE")]
    public function recherche(Request $request){
        
        $key = $request->request->get('key');
        if(empty($key)){
            return $this->redirectToRoute('app_emplois');
        }else{
            $emplois = $this->jobs();
            $found = [];
            foreach ($emplois as  $value) {
                if (strpos($value->getTitre(), $key) or strpos($value->getDescription(), $key)) {
                    $found[] = $value;
                }
            }
            return $this->render('emplois/index.html.twig', [
                'emplois' => $found,
                'key' => $key
            ]);
        }
        
    }

}
