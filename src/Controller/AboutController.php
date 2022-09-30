<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Repository\PageAboutRepository;
use App\Repository\PartenaireRepository;
use App\Repository\PageAccueilRepository;
use App\Repository\RealisationRepository;
use App\Repository\MembreBureauRepository;
use Symfony\Component\HttpFoundation\Response;
use phpDocumentor\Reflection\PseudoTypes\True_;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AboutController extends AbstractController
{
    #[Route('/about', name: 'app_about')]
    public function index(PageAboutRepository $aboutRipo,   PartenaireRepository $partRipo,  PageAccueilRepository $accueilRipo, RealisationRepository $realRipo, MembreBureauRepository $membreRipo): Response
    {
        return $this->render('about/index.html.twig', [
            'page'=>$aboutRipo->find(1),
            'home'=>$accueilRipo->find(1),
            'realisations'=>$realRipo->findBy(['etat'=>'VALIDE']),
            'membres'=>$membreRipo->findBy(['etat'=>True]),
            'partenaires' =>  $partRipo -> findBy(['etat'=>True])
        ]);
    }
}
