<?php

namespace App\Controller;

use App\Entity\Cotisation;
use App\Entity\CotisationTransaction;
use App\Entity\Transaction;
use App\Repository\CotisationRepository;
use App\Repository\MembreRepository;
use App\Repository\WalletRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CotisationController extends AbstractController
{
    #[Route('/cotisation', name: 'app_cotisation')]
    public function index(CotisationRepository $coRepo): Response
    {
        $mycotisations = $coRepo->findAll();
        return $this->render('cotisation/membre.cotisation.html.twig', [
            'controller_name' => 'CotisationController',
            'myCotisations' => $mycotisations
        ]);
    }

    #[Route('/admin/cotisation', name: 'app_cotisation_admin')]
    public function addCotisation(CotisationRepository $coRepo,Request $request,EntityManagerInterface $em): Response
    {
        if(!empty($_POST)){
            //dd($request);
            $cotisation = new Cotisation();
            $cotisation->setTitre($request->request->get('titre'))
                       ->setDescription($request->request->get('description'))
                       ->setMontant($request->request->get('montant'));
            if($request->request->get('type')){
                $cotisation->setType("OBLIGATOIRE");
            }else{
                $cotisation->setType("FACULTATIVE");
            }
            $em->persist($cotisation);
            $em->flush();
            return $this->redirectToRoute('app_cotisation_admin');
        }
        $cotisations = $coRepo->findAll();
        return $this->render('cotisation/admin.cotisation.html.twig', [
            'controller_name' => 'CotisationController',
            'cotisations' => $cotisations
        ]);
    }

    #[Route('/cotisation/clore/{id}', name: 'app_cotisation_cloture')]
    public function cloreCotisation($id,CotisationRepository $coRepo,EntityManagerInterface $em): Response
    {
        $cotisation = $coRepo->find($id);
        $cotisation->setEtat(!$cotisation->getEtat());
        $em->persist($cotisation);
        $em->flush();
        return $this->redirectToRoute('app_cotisation_admin');
    }
    
    #[Route('/cotisation/{id}', name: 'app_cotisation_details')]
    public function detailsCotisation($id,CotisationRepository $coRepo): Response {
        $cotisation = $coRepo->find($id);
        return $this->render('cotisation/cotisation.details.html.twig', [
            'controller_name' => 'CotisationController',
            'cotisation' => $cotisation
        ]);
    }

    #[Route('/admin/cotisation/{id}', name: 'app_cotisation_details_admin')]
    public function detailsCotisationAdmin($id,CotisationRepository $coRepo): Response {
        $cotisation = $coRepo->find($id);
        return $this->render('cotisation/admin.cotisation.details.html.twig', [
            'controller_name' => 'CotisationController',
            'cotisation' => $cotisation
        ]);
    }

    #[Route('/cotisation/make/{id}', name: 'app_cotisation_make')]
    public function makeCotisation($id, WalletRepository $walletRipo,  CotisationRepository $coRepo,EntityManagerInterface $em):Response{
        $user = $this->getUser();
        $cotisation = $coRepo->find($id);
        $wallet = $walletRipo->find($user->getWallet()->getId());
        //verification du solde du portefeuille
        if($cotisation->getMontant()>$wallet->getSolde()){
            $this->addFlash('error', 'Le montant disponible dans votre portefeuille est insuffisant pour effectuer cette transaction');
            return $this->redirectToRoute('app_cotisation_details', ['id'=>$cotisation->getId()]);
        }

        //modification du solde sur le portefeuille
        
        $wallet->setSolde(intval($wallet->getSolde()) - intval($cotisation->getMontant()));
        $em->persist($wallet);

        //création de la transaction
        $tr = new CotisationTransaction();
        $tr ->setDate(new DateTime());
        $tr -> setMontant($cotisation->getMontant());
        $tr -> setType('Cotisation');
        $tr -> setWallet($wallet);
        $em->persist($tr);

        //mise à jour de la cotisation du membre
        $user->addCotisation($cotisation);
        $em->persist($user);

        //mise à jour du solde de la cotisation
        $cotisation->setSolde($cotisation->getSolde()+$cotisation->getMontant());
        $em->persist($cotisation);

        $em->flush();
        return $this->redirectToRoute('app_cotisation');
    }

    #[Route('/cotisation/add/cont', name: 'app_cotisation_add_cont')]
    public function addCont(Request $request, MembreRepository $membreRipo, EntityManagerInterface $em){
        $data = $request->request;
        
        $membre = $membreRipo->findOneBy(["telephone"=>$data->get('full_number')]);

       if($membre == null){
            $this->addFlash('warning', 'Veuillez vérifier le numéro de téléphone');
            return $this->redirectToRoute('app_cotisation_details_admin', ['id'=>$data->get('id')]);
       }
      // dd('ts');
        
    }
}
