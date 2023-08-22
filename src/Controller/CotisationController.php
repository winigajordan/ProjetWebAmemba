<?php

namespace App\Controller;

use DateTime;
use App\Entity\Cotisation;
use App\Entity\Transaction;
use App\Repository\MembreRepository;
use App\Repository\WalletRepository;
use App\Entity\CotisationTransaction;
use App\Repository\CotisationRepository;
use App\Service\PayTech\Payement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CotisationController extends AbstractController
{
    #[Route('/cotisation', name: 'app_cotisation')]
    public function index(CotisationRepository $coRepo): Response
    {
        $mycotisations = $coRepo->findBy(['etat'=>true]);
        return $this->render('cotisation/membre.cotisation.html.twig', [
            'myCotisations' => $mycotisations
        ]);
    }

    #[Route('/admin/cotisation', name: 'app_cotisation_admin'), IsGranted("ROLE_ADMIN")]
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

    #[Route('/admin/cotisation/clore/{id}', name: 'app_cotisation_cloture'), IsGranted("ROLE_ADMIN")]
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

    #[Route('/admin/cotisation/{id}', name: 'app_cotisation_details_admin'), IsGranted("ROLE_ADMIN")]
    public function detailsCotisationAdmin($id,CotisationRepository $coRepo): Response {
        $cotisation = $coRepo->find($id);
        return $this->render('cotisation/admin.cotisation.details.html.twig', [
            'controller_name' => 'CotisationController',
            'cotisation' => $cotisation
        ]);
    }

    #[Route('/admin/cotisation/update/content', name: 'app_admin_cotisation_update_content'), IsGranted("ROLE_ADMIN")]
    public function updateContent(CotisationRepository $coRepo, Request $request, EntityManagerInterface $manager) : Response{
        $data = $request->request;
        $cotisation = $coRepo->find($data->get('id'));
        $cotisation->setTitre($data->get('titre'));
        $cotisation->setDescription($data->get('description'));
        $manager->persist($cotisation);
        $manager->flush();
        return $this->redirectToRoute('app_cotisation_details_admin', array('id'=>$cotisation->getId()) );

    }

    #[Route('/cotisation/with/wallet', name: 'app_cotisation_make'), IsGranted("ROLE_MEMBRE")]
    public function makeCotisationWithWallet(Request $request, WalletRepository $walletRipo,  CotisationRepository $coRepo,EntityManagerInterface $manager) : Response {
        $data = $request->request;
        $user = $this->getUser();
        $cotisation = $coRepo->find($data->get('id'));
        $wallet = $walletRipo->find($user->getWallet()->getId());
        //verification du solde du portefeuille
        if($data->get('montant')>$wallet->getSolde()){
            $this->addFlash('error', 'Le montant disponible dans votre portefeuille est insuffisant pour effectuer cette transaction');
            return $this->redirectToRoute('app_cotisation_details', ['id'=>$cotisation->getId()]);
        }

        //modification du solde sur le portefeuille
        $wallet->setSolde(intval($wallet->getSolde()) - intval($data->get('montant')));
        $manager->persist($wallet);

        //création de la transaction
        $tr = new CotisationTransaction();
        $tr ->setDate(new DateTime());
        $tr -> setMontant($data->get('montant'));
        $tr -> setType('Cotisation');
        $tr -> setWallet($wallet);
        $manager->persist($tr);

        //mise à jour de la cotisation du membre
        $user->addCotisation($cotisation);
        $manager->persist($user);

        //mise à jour du solde de la cotisation
        $cotisation->setSolde($cotisation->getSolde()+$data->get('montant'));
        $manager->persist($cotisation);

        $manager->flush();
        return $this->redirectToRoute('app_cotisation');

    }

    /*

    #[Route('/cotisation/make/wallet/{id}', name: 'app_cotisation_make'), IsGranted("ROLE_MEMBRE")]
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

     */
    #[Route('/cotisation/add/cont', name: 'app_cotisation_add_cont'), IsGranted("ROLE_ADMIN")]
    public function addCont(Request $request,CotisationRepository $cotisationRipo, MembreRepository $membreRipo, EntityManagerInterface $em){
        $data = $request->request;
        
        $membre = $membreRipo->findOneBy(["telephone"=>$data->get('full_number')]);

       if($membre == null){
            $this->addFlash('warning', 'Veuillez vérifier le numéro de téléphone');
            return $this->redirectToRoute('app_cotisation_details_admin', ['id'=>$data->get('id')]);
       } else {
            $cotisation = $cotisationRipo->find($data->get('id'));
            $membre->addCotisation($cotisation);
            $cotisation->setSolde($cotisation->getSolde()+$cotisation->getMontant());
            $em->persist($membre);
            $em->persist($cotisation);
            $em->flush();
            $this->addFlash('okay', 'Contributeur ajouté avec succès');
            return $this->redirectToRoute('app_cotisation_details_admin', ['id'=>$data->get('id')]);
       }
    }

    #[Route('/cotisation/make/{id}', name: 'app_cotisation_make_1'), IsGranted("ROLE_MEMBRE")]
    public function make(CotisationRepository $cotisationRipo,Payement $pay, $id){
        $cotisation =  $cotisationRipo->find($id);
        $url = $pay->payCotisation($cotisation->getMontant(), $id);
        return $this->redirect($url);
    }

    #[Route('/cotisation/sucess/memba/{id}', name: 'app_cotisation_make_1_success'), IsGranted("ROLE_MEMBRE")]
    public function makeSucess(EntityManagerInterface $em ,MembreRepository $membreRipo, CotisationRepository $cotisationRipo,Payement $pay, $id){
        $cotisation =  $cotisationRipo->find($id);
        $user = $membreRipo->find($this->getUser()->getId());
        $user->addCotisation($cotisation);
        $cotisation->setSolde($cotisation->getSolde()+$cotisation->getMontant());
        $em->persist($user);
        $em->persist($cotisation);
        $em->flush();
        return $this->redirectToRoute('app_cotisation');
    }



}
