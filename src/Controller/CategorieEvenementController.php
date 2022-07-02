<?php

namespace App\Controller;

use App\Entity\CategorieEvenement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategorieEvenementRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieEvenementController extends AbstractController
{
    #[Route('/admin/evenement/categorie', name: 'app_categorie_evenement'), IsGranted("ROLE_ADMIN")]
    public function index(CategorieEvenementRepository $ripo): Response
    {
        return $this->render('admin/categorie_evenement/index.html.twig', [
            'controller_name' => 'CategorieEvenementController',
            'categories' => $ripo -> findAll(),
            'nombreCategorie' => count($ripo -> findAll())
        ]);
    }

    #[Route('/admin/evenement/categorie/add', name: 'add_categorie_evenement'), IsGranted("ROLE_ADMIN")]   
    public function add(Request $request, EntityManagerInterface $em){
        $cat = new CategorieEvenement();
        $cat -> setName($request -> request -> get('name'));
        $em -> persist($cat);
        $em -> flush();
        return $this->redirectToRoute("app_categorie_evenement");
    } 
}
