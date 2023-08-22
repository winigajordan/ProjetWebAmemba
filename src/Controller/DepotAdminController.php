<?php

namespace App\Controller;

use App\Repository\DepotRepository;
use App\Repository\WalletRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DepotAdminController extends AbstractController
{
    #[Route('/depot/admin', name: 'app_depot_admin'), IsGranted("ROLE_ADMIN")]
    public function index(DepotRepository $depotRepo,PaginatorInterface $paginator,Request $request): Response
    {
        
        $depots = $depotRepo->findAll();
        $output = $paginator->paginate(
            $depots,
            $request->query->getInt('page',1),
            25
        );
        //dd($output);
        return $this->render('depot_admin/index.html.twig', [
            'controller_name' => 'DepotAdminController',
            'depots' => $output
        ]);
    }

    #[Route('/depot/admin/accept/{ref}', name: 'app_depot_accept'), IsGranted("ROLE_ADMIN")]
    public function accepteDepot(DepotRepository $depotRepo,Request $request,
    EntityManagerInterface $em,$ref,WalletRepository $walletRipo): Response
    {
        
        $depot = $depotRepo->findOneBy(['reference' => $ref]);
        $depot->setEtat("VALIDE");
        //dd($depot);
        $wallet = $walletRipo->find($depot->getWallet()->getId());
        $wallet -> setSolde($wallet->getSolde() + $depot->getMontant());
        $em->persist($wallet);
        $em->persist($depot);
        $em->flush();
        return $this->redirectToRoute("app_depot_admin");
    }

    #[Route('/depot/admin/reject/{ref}', name: 'app_depot_reject'), IsGranted("ROLE_ADMIN")]
    public function rejectDepot(DepotRepository $depotRepo,PaginatorInterface $paginator,Request $request,$ref,
    EntityManagerInterface $em): Response
    {
        
        $depot = $depotRepo->findOneBy(['reference' => $ref]);
        $depot->setEtat("REJETE");
        $em->persist($depot);
        $em->flush();
        return $this->redirectToRoute("app_depot_admin");
    }
}
