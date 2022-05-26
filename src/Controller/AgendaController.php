<?php

namespace App\Controller;

use App\Repository\CategorieEvenementRepository;
use App\Repository\EvenementRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AgendaController extends AbstractController
{
    #[Route('/agenda', name: 'app_agenda')]
    public function index(EvenementRepository $ripo, CategorieEvenementRepository $cateRipo): Response
    {
        $events = $this->events($ripo);
        $past = $events[0];
        $future = $events[1];

        return $this->render('agenda/index.html.twig', [
            'controller_name' => 'AgendaController',
            'future' => $future,
            'past'=> $past,
            'numberPast' => count($past),
            'categories' => $cateRipo -> findAll()
        ]);
    }

    #[Route('/agenda/{id}', name: 'app_agenda_categorie')]
    public function filterByCategorie($id, CategorieEvenementRepository $cateRipo, EvenementRepository $ripo){
       $catEvents = $this->categorieEvenement($id, $cateRipo);
       $events = $this->events($ripo);
       $future = $events[1];

       return $this->render('agenda/index.html.twig', [
           'controller_name' => 'AgendaController',
           'future' => $future,
           'past'=> $catEvents,
           'numberPast' => count($catEvents),
           'categories' => $cateRipo -> findAll(),
           
       ]);
    }

    public function events(EvenementRepository $ripo){
        $today = new DateTime();
        $events =  $ripo -> findBy([], array('date' => 'ASC'));
        $future = [];
        $past = [];
        foreach ($events as $var) {
            if ($var -> getDate() < $today) {
                $past[] = $var;
            } else {
                 $future[] = $var;
            }
        }
        return [$past, $future];
    }

    public function categorieEvenement($id, $cateRipo){
        $cat = $cateRipo -> find(intval($id));
        if ($cat == null){
            return [];
        }else{
            $evs = $cat -> getEvenements();
        $catEvs = [];
        foreach ($evs as $key => $value) {
            $catEvs[]=$value;
        }
        return $catEvs;
        }
        
    }
}
