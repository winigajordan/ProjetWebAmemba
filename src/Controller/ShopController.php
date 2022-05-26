<?php

namespace App\Controller;

use App\Repository\CategorieProduitRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    #[Route('/shop', name: 'app_shop')]
    public function index(ProduitRepository $prodRepo,CategorieProduitRepository $catRepo): Response
    {
        if(!empty($_POST)){
            
        }
        $categories = $catRepo->findAll();
        $produits=$prodRepo->findBy(
            ['etat'=>true],
            ['id'=>'DESC']
        );
        return $this->render('shop/index.html.twig', [
            'controller_name' => 'ShopController',
            'produits' => $produits,
            'count' => count($produits),
            'categories' => $categories            
        ]);
    }

    #[Route('/shop/details/{slug}', name: 'app_produit_detail')]
    public function details($slug,ProduitRepository $prodRepo): Response
    {
        $produit=$prodRepo->findOneBy(['slug'=>$slug]);
        $produitsSimilaires = $prodRepo->findSimilaires($produit);
        //dd($produit);
        return $this->render('shop/produit.details.html.twig', [
            'controller_name' => 'ShopController',
            'produit' => $produit,
            'similaires' => $produitsSimilaires
        ]);
    }

    #[Route('/shop/categorie/{id}', name: 'app_produit_by_category')]
    public function byCategorie($id,CategorieProduitRepository $catRepo,
    ProduitRepository $prodRepo): Response
    {
        $categories = $catRepo->findAll();
        $categorie= $catRepo->find($id);
        //$produits = $categorie->getProduits();
        $produits=$prodRepo->findBy(
            ['etat'=>true,'categorie'=>$categorie],
            ['id'=>'DESC']
        );
        return $this->render('shop/index.html.twig', [
            'controller_name' => 'ShopController',
            'produits' => $produits,
            'count' => count($produits),
            'categories' => $categories  
        ]);
    }
}
