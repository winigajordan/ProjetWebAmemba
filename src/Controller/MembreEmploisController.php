<?php

namespace App\Controller;

use DateTime;
use App\Entity\OffreEmplois;
use App\Repository\MembreRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OffreEmploisRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MembreEmploisController extends AbstractController
{

    public function __construct(
        OffreEmploisRepository $offreRipo,
        EntityManagerInterface $em,
        MembreRepository $memebreRipo
    ){
        $this->offreRipo = $offreRipo;
        $this -> em = $em;
        $this -> membreRipo = $memebreRipo;
    }

    #[Route('/membre/emplois', name: 'app_membre_emplois'), IsGranted("ROLE_MEMBRE")]
    public function index(): Response
    {
        return $this->render('membre_emplois/index.html.twig', [
            'controller_name' => 'MembreEmploisController',
            'today'=> date_format(new DateTime(),'Y-m-d'),
            'emplois'=>$this->offreRipo->findBy(['membre'=>$this->membreRipo->find($this->getUser()->getId())]),
        ]);
    }


    #[Route('/membre/emplois/add', name: 'membre_emplois_add', methods:('POST')), IsGranted("ROLE_MEMBRE")]
    public function ajout(Request $request)
    {
        
        $offre = new OffreEmplois();
        $offre -> setTitre($request->request->get('titre'));
        $offre -> setSlug($this->slg($request->request->get('titre')));
        $offre -> setDomaine($request->request->get('domaine'));
        $offre -> setDescription($request->request->get('description'));
        $offre -> setCreateAt(new DateTime());
        $offre -> setEndAt(new DateTime($request->request->get('date')));
        if($request->request->get('archive')){
            $offre -> setEtat('ARCHIVE');
        }else{
            $offre -> setEtat('EN COURS');
        }
        $offre ->setMembre($this->membreRipo->find($this->getUser()->getId()));
        $this->em->persist($offre);
        $this->em->flush();
        return $this->redirectToRoute('app_membre_emplois');
    }

    public function slg($slug){
        return str_replace(" ","-",strtolower($slug));
    }

    #[Route('/membre/emplois/details/{slug}', name: 'membre_emplois_details'), IsGranted("ROLE_MEMBRE")]
    public function details($slug, Request $request){
        $selected = $this->offreRipo->findOneBy(['membre'=>$this->membreRipo->find($this->getUser()->getId()), 'slug'=>$slug]);
        if ($selected == null) {
            return $this->redirectToRoute('app_membre_emplois');
        }
        return $this->render('membre_emplois/index.html.twig', [
            'controller_name' => 'MembreEmploisController',
            'today'=> date_format(new DateTime(),'Y-m-d'),
            'emplois'=>$this->offreRipo->findBy(['membre'=>$this->membreRipo->find($this->getUser()->getId())]),
            'selected'=>$selected,
        ]);
    }

    #[Route('/membre/emplois/update', name: 'membre_emplois_update', methods:('POST')) ,IsGranted("ROLE_MEMBRE")]
    public function update(Request $request){
        $offre = $this->offreRipo->find(intval($request->request->get('id')));
        $offre -> setTitre($request->request->get('titre'));
        $offre -> setSlug($this->slg($request->request->get('titre')));
        $offre -> setDomaine($request->request->get('domaine'));
        $offre -> setDescription($request->request->get('description'));
        $offre -> setCreateAt(new DateTime());
        $offre -> setEndAt(new DateTime($request->request->get('date')));
        if($request->request->get('archive')){
            $offre -> setEtat('ARCHIVE');
        }else{
            $offre -> setEtat('EN COURS');
        }
        $this->em->persist($offre);
        $this->em->flush();
        return $this->redirectToRoute('app_membre_emplois');
    }
    
}
