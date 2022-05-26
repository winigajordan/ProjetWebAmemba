<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(SessionInterface $session,ProduitRepository $repoP): Response
    {
        $panier=$session->get("panier",[]);
        $panierWithData=[];
        $total=0;
        foreach($panier as $id=>$quantite){
            $panierWithData[]=[
                'produit' => $repoP->find($id),
                'quantite' => $quantite
            ];
            $totalI=$repoP->find($id)->getPrix()*$quantite;
            $total+=$totalI;
        }
        return $this->render('panier/panier.html.twig', [
            'controller_name' => 'PanierController',
            'items' => $panierWithData,
            'total' => $total
        ]);
    }

    #[Route('/panier/add/{id}', name: 'app_panier_add')]
    public function addToCart($id,SessionInterface $session){
        $panier=$session->get("panier",[]);
        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id]=1;
        }
        $session->set("panier",$panier);
        return $this->redirectToRoute("app_panier");
    }

    #[Route('/panier/update', name: 'app_panier_update')]
    public function updateCart(Request $request,SessionInterface $session){
        //dd($request);
        $panier=$session->get("panier",[]);
        foreach($request->request as $id=>$quantite){
            //dd(intval($quantite));
            $panier[$id]=intval($quantite);
            if(intval($quantite)<=0){
                unset($panier[$id]);
            }
        }
        $session->set('panier',$panier);

        return $this->redirectToRoute("app_panier");
    }

    #[Route('/panier/remove/{id}', name: 'app_panier_remove')]
    public function removeFromCart(Request $request,SessionInterface $session,$id){
        $panier=$session->get("panier",[]);
        if(!empty($panier[$id])){
            unset($panier[$id]);
        }
        $session->set('panier',$panier);
        return $this->redirectToRoute("app_panier");
    }
}
