<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategorieProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShopController extends AbstractController
{
    #[Route('/shop', name: 'app_shop')]
    public function index(ProduitRepository $prodRepo,
                          CategorieProduitRepository $catRepo,
                          Request $request,
                          PaginatorInterface $paginator): Response
    {
        $categories = $catRepo->findAll();
        if(!empty($_GET)& isset($_GET['search'])){

            $options = $prodRepo->findBy(
                ['etat'=>true],
                ['id'=>'DESC']
            );
            $key = $_GET['search'];
            $found = [];
            foreach($options as $pro){
                if (strpos($pro->getLibelle(), $key)) {
                    $found[] = $pro;
                }
            }
            $output = $paginator->paginate(
                $found,
                $request->query->getInt('page',1),
                15
            );
            return $this->render('shop/index.html.twig', [
                'controller_name' => 'ShopController',
                'produits' => $output,
                'count' => count($found),
                'categories' => $categories
            ]);
        }
        $produits=$prodRepo->findBy(
            ['etat'=>true],
            ['id'=>'DESC']
        );
        $output = $paginator->paginate(
            $produits,
            $request->query->getInt('page',1),
            15
        );
        return $this->render('shop/index.html.twig', [
            'controller_name' => 'ShopController',
            'produits' => $output,
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
    public function byCategorie($id,
                                CategorieProduitRepository $catRepo,
                                ProduitRepository $prodRepo,
                                Request $request,
                                PaginatorInterface $paginator
    ): Response
    {
        $categories = $catRepo->findAll();
        $categorie= $catRepo->find($id);
        //$produits = $categorie->getProduits();
        $produits=$prodRepo->findBy(
            ['etat'=>true,'categorie'=>$categorie],
            ['id'=>'DESC']
        );
        $output = $paginator->paginate(
            $produits,
            $request->query->getInt('page',1),
            15
        );
        return $this->render('shop/index.html.twig', [
            'controller_name' => 'ShopController',
            'produits' => $output,
            'count' => count($produits),
            'categories' => $categories
        ]);
    }
}