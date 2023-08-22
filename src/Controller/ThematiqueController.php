<?php

namespace App\Controller;

use App\Entity\Thematique;
use App\Repository\ThematiqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ThematiqueController extends AbstractController
{
    #[Route('admin/thematique', name: 'app_thematique'), IsGranted("ROLE_ADMIN")]
    public function index(ThematiqueRepository $thRepo): Response
    {
        $thematiques = $thRepo->findAll();
        return $this->render('thematique/index.html.twig', [
            'thematiques' => $thematiques
        ]);
    }

    #[Route('/admin/thematique/add', name: 'app_thematique_add'), IsGranted("ROLE_ADMIN")]
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

    #[Route('admin/thematique/update/{id}', name: 'app_thematique_update'), IsGranted("ROLE_ADMIN")]
    public function updateThematique(ThematiqueRepository $thRepo,$id,EntityManagerInterface $em,Request $request): Response
    {
        $cat = $thRepo->find($id);
        if(!empty($_POST)){
            $cat->setLibelle($request->request->get('libelle'));
            $em->persist($cat);
            $em->flush();
            return $this->redirectToRoute('app_thematique');
        }else{
            
            $thematiques = $thRepo->findAll();
            return $this->render('thematique/index.html.twig', [
                'thematiques' => $thematiques,
                'thematique' => $cat
            ]);
        }
        
    }

    #[Route('admin/thematique/archive/{id}', name: 'app_thematique_archive'), IsGranted("ROLE_ADMIN")]
    public function removeThematique(ThematiqueRepository $thRepo,EntityManagerInterface $em,$id):  Response
    {
        $th = $thRepo->find($id);
        $th->setStatus(!($th->getStatus()));
        $em->persist($th);
        $em->flush();
        return $this->redirectToRoute('app_thematique');
    }
}
