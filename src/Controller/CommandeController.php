<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Achat;
use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\DetailCommande;
use Doctrine\ORM\EntityManager;
use App\Service\PayTech\Payement;
use App\Repository\MembreRepository;
use App\Repository\WalletRepository;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    #[Route('/checkout', name: 'app_commande')]
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
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
            'total' => $total,
            'items' => $panierWithData
        ]);
    }

    #[Route('/commande/add', name: 'app_commande_add')]
    public function addCommande(SessionInterface $session,
    EntityManagerInterface $em,ProduitRepository $prodRepo,
    CommandeRepository $commRepo, Payement $payement,Request $request): Response
    {
        if($this->getUser()){
            if(!empty($_POST)){
                //dd($request->request);
                $commande=new Commande();
                $commande->setPrixTotal(0);
                $panier=$session->get("panier",[]);
                foreach($panier as $id=>$quantite){
                    $detailCommande = new DetailCommande();
                    $produit = $prodRepo->find($id);
                    if($produit->getQteStock()-$quantite<0){
                        $produit->setQteStock(0);
                    }else{
                        $produit->setQteStock($produit->getQteStock()-$quantite);
                    }
                    $em->persist($produit);
                    $detailCommande->setProduit($produit);
                    $detailCommande->setPrix($produit->getPrix()*$quantite);
                    $detailCommande->setQuantite($quantite);
                    $commande->addDetailCommande($detailCommande);
                    $em->persist($detailCommande);
                    $commande->setPrixTotal($commande->getPrixTotal()+$detailCommande->getPrix());
                }
                $commande->setEtat("EN ATTENTE DE CONFIRMATION");
                $commande->setClient($this->getUser());
                $commande->setDate(new DateTime());
                $lastCommande = $commRepo->findBy([],['id'=>'DESC'],1);
                if(count($lastCommande)==0){
                    $commande->setReference("REF001");
                }
                else{
                    $idRef = strval($lastCommande[0]->getId()+1);
                    $commande->setReference("REF00".$idRef);
                }
                $commande->setMoyen($request->request->get('moyen'));
                $commande->setNumero($request->request->get('numero'));
                $em->persist($commande);                
                $em->flush();
                $session->set("panier",[]);
                return $this->redirectToRoute('app_commandes_client');
            } 
        }
        return $this->redirectToRoute('add_client');
    }

    #[Route('/commande/add/wallet', name: 'app_commande_add_with_wallet'), IsGranted("ROLE_MEMBRE")]
    public function addCommandeWithWallet(SessionInterface $session, 
    EntityManagerInterface $em,
    ProduitRepository $prodRepo, 
    CommandeRepository $commRepo,
    MembreRepository $membreRepository,
    WalletRepository $walletRepository){
        if($this->getUser()){
            $commande=new Commande();
            $commande->setPrixTotal(0);
            $panier=$session->get("panier",[]);
            foreach($panier as $id=>$quantite){
                $detailCommande = new DetailCommande();
                $produit = $prodRepo->find($id);
                if($produit->getQteStock()-$quantite<0){
                    $produit->setQteStock(0);
                }else{
                    $produit->setQteStock($produit->getQteStock()-$quantite);
                }
                $em->persist($produit);
                $detailCommande->setProduit($produit);
                $detailCommande->setPrix($produit->getPrix()*$quantite);
                $detailCommande->setQuantite($quantite);
                $commande->addDetailCommande($detailCommande);
                $em->persist($detailCommande);
                $commande->setPrixTotal($commande->getPrixTotal()+$detailCommande->getPrix());
            }
            //dd($commande->getPrixTotal());
            $commande->setEtat("EN COURS");
            $commande->setClient($this->getUser());
            $commande->setDate(new DateTime());
            $lastCommande = $commRepo->findBy([],['id'=>'DESC'],1);
            if(count($lastCommande)==0){
                $commande->setReference("REF001");
            }
            else{
                $idRef = strval($lastCommande[0]->getId()+1);
                $commande->setReference("REF00".$idRef);
            }
            $commande->setMoyen("WALLET");
            $em->persist($commande);
            
            
            $wallet = $this->getUser()->getWallet();
            $newSolde = intval($wallet -> getSolde())-intval($commande->getPrixTotal());
            $wallet->setSolde($newSolde);
            $em-> persist($wallet);

            $achat = new Achat();
            $achat -> setDate(new DateTime());
            $achat -> setMontant(floatval($commande->getPrixTotal()));
            $achat -> setType('Achat');
            $achat -> setWallet($wallet);
            $achat -> setCommande($commande);
            $em -> persist($achat);
            $session->set("panier",[]);

            $em->flush();
           
            return $this->redirectToRoute('app_commandes_client');   
        }
        return $this->redirectToRoute('add_client');
    }



    #[Route('/client/commandes', name: 'app_commandes_client')]
    public function clientCommandes(SessionInterface $session,CommandeRepository $comRepo,
    Request $request,PaginatorInterface $paginator): Response
    {
        $user=$this->getUser();
        if(!empty($_POST)){
            $etat = $request->request->get('etat');
            $commandes= $comRepo->findBy(['client'=>$user,'etat'=>$etat],['date'=>'DESC']);
        }else{
            $commandes= $comRepo->findBy(['client'=>$user],['date'=>'DESC']);
        }
        $output = $paginator->paginate(
            $commandes,
            $request->query->getInt('page',1),
            25
        );      
        return $this->render('commande/client.commandes.html.twig', [
            'controller_name' => 'CommandeController',
            'commandes' => $output
        ]);
    }

    #[Route('/admin/commandes', name: 'app_commandes'), IsGranted("ROLE_ADMIN")]
    public function allCommandes(SessionInterface $session,CommandeRepository $comRepo,PaginatorInterface $paginator,
    Request $request): Response
    {
        $commandes= $comRepo->findAll();
        $output = $paginator->paginate(
            $commandes,
            $request->query->getInt('page',1),
            25
        ); 
        return $this->render('commande/admin.commandes.html.twig', [
            'controller_name' => 'CommandeController',
            'commandes' => $output
        ]);
    }

    #[Route('/admin/commandes/details/{id}', name: 'app_commande_details'), IsGranted("ROLE_ADMIN")]
    public function commandeDetails($id,CommandeRepository $comRepo,EntityManagerInterface $em,
    Request $request): Response
    {
        $commande= $comRepo->find($id);
        if(!empty($_POST)){
            $commande->setEtat($request->request->get("etat"));
            $em->persist($commande);
            $em->flush();
        }
        
        return $this->render('commande/admin.commande.detail.html.twig', [
            'controller_name' => 'CommandeController',
            'commande' => $commande
        ]);
    }

    #[Route('/client/commandes/details/{id}', name: 'app_client_commande_details')]
    public function commandeClientDetails($id,CommandeRepository $comRepo): Response
    {
        $commande= $comRepo->find($id);        
        return $this->render('commande/client.commande.details.html.twig', [
            'controller_name' => 'CommandeController',
            'commande' => $commande
        ]);
    }

    #[Route('/commande/success/{ref}', name: 'app_client_commande_success')]
    public function commandeValide($ref, SessionInterface $session,CommandeRepository $cmdRipo, EntityManagerInterface $em): Response
    {    
        $commande = $cmdRipo->findOneBy(["reference"=>$ref]);
        //dd($commande);
        $commande -> setEtat('EN COURS');
        $em->persist($commande);
        $em->flush();
        $session->set("panier",[]);
        return $this->redirectToRoute('app_commandes_client');
    }

}
