<?php

namespace App\Controller;

use App\Entity\Partenaire;
use App\Repository\PartenaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\PseudoTypes\True_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPartenaireController extends AbstractController
{

    public function __construct(EntityManagerInterface $em, PartenaireRepository $partRipo){
        $this -> em = $em;
        $this -> partRipo = $partRipo;
    }

    #[Route('/admin/partenaire', name: 'app_admin_partenaire')]
    public function index(): Response
    {
        return $this->render('admin/admin_partenaire/index.html.twig', [
            'partenaires' => $this -> partRipo -> findAll()
        ]);
    }

    #[Route('/admin/partenaires/add', name: 'app_admin_partenaire_add')]
    public function add(Request $request): Response
    {
        $part = new Partenaire();
        $part -> setNom($request->request->get('nom'));
        $part -> setEtat(True);
        $part -> setDescription($request->request->get('description'));
        $img=$request->files->get("logo"); 
        $imageName=uniqid().'.'.$img->guessExtension(); 
        $img->move($this->getParameter("partenaires"),$imageName);          
        $part->setLogo($imageName);
        $this->em->persist($part);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_partenaire');
    }

    #[Route('/admin/partenaire/update/{id}', name: 'app_admin_partenaire_update')]
    public function update($id):Response
    {
        $part = $this->partRipo->find($id);
        $part->setEtat(!$part->getEtat());
        $this->em->persist($part);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_partenaire');
    }    

}
