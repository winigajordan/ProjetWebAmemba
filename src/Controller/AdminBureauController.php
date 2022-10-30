<?php

namespace App\Controller;

use App\Entity\MembreBureau;
use App\Entity\PostesBureau;
use App\Repository\MembreBureauRepository;
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
            'membres'=>$this->membreRipo->findAll()
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

    #[Route('/admin/bureau/add/membre', name: 'app_add_membre')]
    public function addMembre(Request $request): Response
    {
        $membre = new MembreBureau();
        $membre -> setNomComplet($request->request->get('nom'));
        $membre -> setTelephone($request->request->get('promotion'));
        $membre -> setFonction($this->posteRipo->find($request->request->get('poste')));
        $membre -> setEtat(True);

        $img=$request->files->get("pp"); 
        $imageName=uniqid().'.'.$img->guessExtension(); 
        $img->move($this->getParameter("bureau_directory"),$imageName);          
        $membre->setImg($imageName);

        $this->em->persist($membre);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_bureau');
    }

    #[Route('/admin/bureau/update/{id}', name: 'app_update_membre')]
    public function updateMembre($id): Response
    {
        $membre = $this->membreRipo->find($id);
        $membre -> setEtat(!$membre->getEtat());
        $this->em->persist($membre);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_bureau');
    }
}
