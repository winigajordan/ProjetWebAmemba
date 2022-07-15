<?php

namespace App\Controller;

use DateTime;
use App\Repository\EvenementRepository;
use App\Repository\PartenaireRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategorieEvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AgendaController extends AbstractController
{
    #[Route('/agenda', name: 'app_agenda')]
    public function index(
        EvenementRepository $ripo, 
        CategorieEvenementRepository $cateRipo,
        PaginatorInterface $paginator,
        PartenaireRepository $partRipo
        ): Response
    {
        $events = $this->events($ripo);
        $past = $events[0];
        $future = $events[1];

        return $this->render('agenda/index.html.twig', [
            'controller_name' => 'AgendaController',
            'future' => $future,
            'past'=> $past,
            'numberPast' => count($past),
            'categories' => $cateRipo -> findAll(),
            'partenaires' =>  $partRipo -> findBy(['etat'=>True])
        ]);
    }

    #[Route('/agenda/{id}', name: 'app_agenda_categorie')]
    public function filterByCategorie($id, CategorieEvenementRepository $cateRipo, EvenementRepository $ripo,   PartenaireRepository $partRipo){
       $catEvents = $this->categorieEvenement($id, $cateRipo);
       $events = $this->events($ripo);
       $future = $events[1];

       return $this->render('agenda/index.html.twig', [
           'controller_name' => 'AgendaController',
           'future' => $future,
           'past'=> $catEvents,
           'numberPast' => count($catEvents),
           'categories' => $cateRipo -> findAll(),
           'partenaires' =>  $partRipo -> findBy(['etat'=>True])
           
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
