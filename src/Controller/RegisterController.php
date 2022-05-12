<?php

namespace App\Controller;

use App\Entity\Demande;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{

    #[Route('/register', name: 'app_register')]
    public function index(): Response
    {
        return $this->render('register/index.html.twig', [
            'controller_name' => 'RegisterController',
            'txtRegister' => 'Remplissez le formulaire pour une demande de création de compte',
        ]);
    }

    #[Route('/register/add', name: 'add_demande')]
    public function aaddDemande(Request $request, EntityManagerInterface $em): Response {
       $demande = new Demande();
       $demande -> setNom($request -> request -> get('nom'));
       $demande -> setPrenom($request -> request -> get('prenom'));
       $demande -> setMail($request -> request -> get('email'));
       $demande -> setPromotion($request -> request -> get('promotion'));
       $demande -> setPays($request -> request -> get('pays'));
       $demande -> setVille($request -> request -> get('ville'));
       $demande -> setEtat('EN COURS');
       $demande -> setDate(new DateTime());  

       $em -> persist($demande);
       $em -> flush();

       return $this->redirectToRoute("app_register");
    }
}
