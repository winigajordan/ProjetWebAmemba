<?php

namespace App\Controller;

use Mail;
use DateTime;
use App\Entity\Demande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
       $nom = $demande -> getNom()." ".$demande -> getPrenom();
       $date = date_format($demande -> getDate(),'Y/m/d-H:i:s');
       $content = "Une demande d'adhésion vien d'être effectué par $nom \n Date et heure : $date \n Veuillez vous connecter pour traiter la demande";
       $mail = new Mail();
       $mail -> send("winiga-jordane.rema@ism.edu.sn", "", "Demande d'adhésion", $content);
       return $this->redirectToRoute("app_register");
    }
}
