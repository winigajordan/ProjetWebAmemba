<?php

namespace App\Controller;

use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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

    #[Route('/admin/entreprise', name: 'app_admin_entreprise'), IsGranted("ROLE_ADMIN")]
    public function index(): Response
    {
        return $this->render('admin/admin_entreprise/index.html.twig', [
            'controller_name' => 'AdminEntrepriseController',
            'entreprises' => $this->entRipo->findAll(),
        ]);
    }

    #[Route('/admin/entreprise/details/{slug}', name: 'admin_entreprise_details'), IsGranted("ROLE_ADMIN")]
    public function details($slug)
    {
        $selected = $this->entRipo->findOneBy(['slug'=>$slug]);
        if ($selected == null) {
            return $this->redirectToRoute('app_admin_entreprise');
        }
        return $this->render('admin/admin_entreprise/index.html.twig', [
            'controller_name' => 'AdminEntrepriseController',
            'entreprises' => $this->entRipo->findAll(),
            'selected_startup' => $selected
        ]);
    }

    #[Route('/admin/entreprise/update/{slug}/{etat}', name: 'admin_entreprise_update'), IsGranted("ROLE_ADMIN")]
    public function update($slug,$etat)
    {
        $entreprise =  $this->entRipo->findOneBy(['slug'=>$slug]);
        $entreprise -> setEtat($etat);
        $this->em->persist($entreprise);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_entreprise');
    }
}
