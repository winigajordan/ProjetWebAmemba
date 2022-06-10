<?php

namespace App\Controller;

use DateTime;
use App\Repository\EvenementRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{

    public function __construct(
        EvenementRepository $eventRipo
        )
    {
        $this-> eventRipo = $eventRipo;
    }

    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {

        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'events' => $this->events(),
        ]);
    }

    public function events(){
        $today = new DateTime();
        $events =  $this -> eventRipo -> findBy([], array('date' => 'ASC'));
        $future = [];
        foreach ($events as $var) {
            if ($var -> getDate() > $today) {
                $future[] = $var;
            }
        }
        return $future;
    }
}
