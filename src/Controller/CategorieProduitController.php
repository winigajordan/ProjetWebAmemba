<?php

namespace App\Controller;

use App\Entity\CategorieProduit;
use App\Repository\CategorieProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieProduitController extends AbstractController
{
    #[Route('/admin/produit/categorie', name: 'app_categorie_produit')]
    public function index(CategorieProduitRepository $catRepo): Response
    {
        $categories=$catRepo->findAll();
        return $this->render('categorie_produit/index.html.twig', [
            'controller_name' => 'CategorieProduitController',
            'categories' => $categories
        ]);
    }

    #[Route('/admin/produit/categorie/add', name: 'app_produit_categorie_add')]
    public function addCategorie(Request $request,EntityManagerInterface $em): Response
    {
        if(!empty($_POST)){
            $cat = new CategorieProduit();
            $cat->setLibelle($request->request->get('libelle'));
            $em->persist($cat);
            $em->flush();
        }
        return $this->redirectToRoute('app_categorie_produit');
    }
}
