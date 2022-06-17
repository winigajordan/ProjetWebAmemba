<?php

namespace App\Controller;

use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminEntrepriseController extends AbstractController
{

    public function __construct(
        EntrepriseRepository $entrepriseRepository, 
        EntityManagerInterface $em,
        )
    {
        $this-> entRipo = $entrepriseRepository;
        $this-> em = $em;
    }

    #[Route('/admin/entreprise', name: 'app_admin_entreprise')]
    public function index(): Response
    {
        return $this->render('admin/admin_entreprise/index.html.twig', [
            'controller_name' => 'AdminEntrepriseController',
            'entreprises' => $this->entRipo->findAll(),
        ]);
    }

    #[Route('/admin/entreprise/details/{slug}', name: 'admin_entreprise_details')]
    public function details($slug)
    {
        return $this->render('admin/admin_entreprise/index.html.twig', [
            'controller_name' => 'AdminEntrepriseController',
            'entreprises' => $this->entRipo->findAll(),
            'selected_startup' => $this->entRipo->findOneBy(['slug'=>$slug])
        ]);
    }

    #[Route('/admin/entreprise/update/{slug}/{etat}', name: 'admin_entreprise_update')]
    public function update($slug,$etat)
    {
        $entreprise =  $this->entRipo->findOneBy(['slug'=>$slug]);
        $entreprise -> setEtat($etat);
        $this->em->persist($entreprise);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_entreprise');
    }
}
