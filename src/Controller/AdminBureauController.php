<?php

namespace App\Controller;

use App\Entity\MembreBureau;
use App\Entity\PostesBureau;
use App\Repository\MembreBureauRepository;
use App\Repository\MembreRepository;
use App\Repository\PostesBureauRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\PseudoTypes\True_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBureauController extends AbstractController
{

    public function __construct(EntityManagerInterface $em, PostesBureauRepository $posteRipo, MembreBureauRepository $membreRipo){
        $this->em = $em;
        $this->posteRipo = $posteRipo;
        $this->membreRipo = $membreRipo;
    }

    #[Route('/admin/bureau', name: 'app_admin_bureau')]
    public function index(): Response
    {
        return $this->render('admin/admin_bureau/index.html.twig', [
            'postes'=>$this->posteRipo->findAll(),
            'membres'=>$this->membreRipo->findBy(['etat'=>true])
        ]);
        
    }

    #[Route('/admin/bureau/add', name: 'app_add_poste')]
    public function addPoste(Request $request): Response
    {
        $poste = new PostesBureau();
        $poste -> setLibelle($request->request->get('libelle'));
        $this->em->persist($poste);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_bureau');
    }

    #[Route('/admin/bureau/search', name: 'app_search')]
    public function search(Request $request, MembreRepository $membreRipo): Response
    {
        $membre = $membreRipo->findOneBy(['email'=>$request->request->get('email')]);
        if ($membre==null) {
            $this->addFlash('error', 'Aucune membre n\'a cette adresse email dans la base de donnée');
            return $this->redirectToRoute('app_admin_bureau');
        }
        
       return $this->render('admin/admin_bureau/index.html.twig', [
        'postes'=>$this->posteRipo->findAll(),
        'membres'=>$this->membreRipo->findAll(),
        'membreFound'=>$membre
    ]);
    }

    #[Route('/admin/bureau/add/membre', name: 'app_add_membre')]
    public function addMembre(Request $request, MembreRepository $membreRipo): Response
    {
        //dd($request->request->get('id'));
        $selected = $membreRipo->find($request->request->get('id'));
        $poste = $this->posteRipo->find($request->request->get('poste'));
        
        $membre = new MembreBureau();
        $membre -> setNomComplet(strtoupper($selected->getPrenom().' '.$selected->getNom()));
        $membre -> setFonction($poste);
        $membre->setMembre($selected);
        $membre -> setEtat(True);

        $selected->setRoleAmicale(strtoupper($poste->getLibelle()));

        $this->em->persist($selected);
        $this->em->persist($membre);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_bureau');
    }

    #[Route('/admin/bureau/update/{id}', name: 'app_update_membre')]
    public function updateMembre($id, MembreRepository $membreRipo): Response
    {
        $membre = $this->membreRipo->find($id);
        $membre -> setEtat(!$membre->getEtat());
        $mb = $membreRipo->find($membre->getMembre()->getId());
        if ($mb->getRoleAmicale()=="MEMBRE") {
            $mb->setRoleAmicale($membre->getFonction()->getLibelle());
        } else {
            $mb->setRoleAmicale("MEMBRE");
        }
        $this->em->persist($membre);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_bureau');
    }
}
