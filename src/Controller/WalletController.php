<?php

namespace App\Controller;

use DateTime;
use App\Entity\Depot;
use App\Repository\MembreRepository;
use App\Repository\WalletRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TransactionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class WalletController extends AbstractController
{

    private  WalletRepository $walletRipo;
    private MembreRepository $membreRepository;
    private EntityManagerInterface $em;
    private TransactionRepository $transactionRipo;
    

    public function __construct(
        WalletRepository $walletRipo, 
        MembreRepository $membreRepository,
        EntityManagerInterface $em,
        TransactionRepository $transactionRipo,
        )
    {
        $this-> walletRipo = $walletRipo;
        $this-> membreRepository = $membreRepository;
        $this-> em = $em;
        $this-> transactionRipo = $transactionRipo;
    }



    #[Route('/wallet', name: 'app_wallet'), IsGranted("ROLE_MEMBRE")]
    public function index(): Response
    {
        $id = $this->getUser()->getId();
        $membre = $this->membreRepository->find($id);
        $wallet = $this -> walletRipo -> findOneBy(['membre'=>$membre]);
        $transactions = $this -> transactionRipo -> findBy(['wallet'=>$wallet]);
        return $this->render('wallet/index.html.twig', [
            'controller_name' => 'WalletController',
            'solde' => $wallet->getSolde(),
            'transactions' => $transactions
        ]);
    }

    #[Route('/wallet/recharge', name: 'app_wallet_recharge'), IsGranted("ROLE_MEMBRE")]
    public function recharge(Request $request){
        $solde = $request -> request -> get('montant');
        $membre = $this->membreRepository->find($this->getUser()->getId());
        $wallet = $this -> walletRipo -> findOneBy(['membre'=>$membre]);
        $wallet -> setSolde($wallet->getSolde()+intval($solde));
        $this->em ->persist($wallet);

        $depot = new Depot();
        $depot -> setDate(new DateTime());
        $depot -> setWallet($wallet);
        $depot -> setMontant(floatval($solde));
        $depot -> setType('Depôt');
        $this->em ->persist($depot);
        
        $this -> em -> flush();
        return $this-> redirectToRoute('app_wallet');
        
    }
}
