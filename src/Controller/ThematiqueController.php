<?php

namespace App\Controller;

use App\Entity\Thematique;
use App\Repository\ThematiqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ThematiqueController extends AbstractController
{
    #[Route('/thematique', name: 'app_thematique')]
    public function index(ThematiqueRepository $thRepo): Response
    {
        $thematiques = $thRepo->findAll();
        return $this->render('thematique/index.html.twig', [
            
            'thematiques' => $thematiques
        ]);
    }

    #[Route('/thematique/add', name: 'app_thematique_add')]
    public function addThematique(Request $request,EntityManagerInterface $em): Response
    {
        if(!empty($_POST)){
            $theme = new Thematique();
            $theme->setLibelle($request->request->get('libelle'));
            $em->persist($theme);
            $em->flush();
        }
        return $this->redirectToRoute('app_thematique');
    }
}
