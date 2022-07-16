<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use DateTime;
use App\Repository\EvenementRepository;
use App\Repository\PageAccueilRepository;
use App\Repository\PartenaireRepository;
use phpDocumentor\Reflection\PseudoTypes\True_;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{

    public function __construct(
        EvenementRepository $eventRipo,
        PageAccueilRepository $accueilRepository,
        PartenaireRepository $partRipo,
        ArticleRepository $articleRepo
        )
    {
        $this-> eventRipo = $eventRipo;
        $this-> accueilRepository = $accueilRepository;
        $this->partRipo = $partRipo;
        $this->articleRipo = $articleRepo;
    }

    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {
        return $this->render('accueil/index.html.twig', [
            'events' => $this->events(),
            'page' =>  $this-> accueilRepository->find(1),
            'partenaires' => $this->partRipo->findBy(['etat'=>True]),
            'articles'=>$this->art()
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

    public function art(){
        $articles = $this->articleRipo->findBy(['lisibilite'=>true],['publishedAt'=>'DESC']);
        if(sizeof($articles)<5){
            return $articles;
        } else {
           return array_slice($articles, 0, 5);
        }
    }
}
