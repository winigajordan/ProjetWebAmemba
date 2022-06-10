<?php

namespace App\Controller;

use Mail;
use DateTime;
use App\Entity\Client;
use App\Entity\Demande;
use App\Service\Mail\ApiMailJet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    private $encoder;
    public function __construct(UserPasswordHasherInterface $encoder){
        $this->encoder=$encoder;
    }

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
       $mail = new ApiMailJet();
       $mail -> send("winiga-jordane.rema@ism.edu.sn", "", "Demande d'adhésion", $content);
       return $this->redirectToRoute("app_register");
    }


    #[Route('/register/client', name: 'add_client')]
    public function addClient(Request $request, EntityManagerInterface $em,
    SessionInterface $session): Response {
        if (!empty($_POST)){
            $client=new Client();
            $client->setNom($request -> request -> get('nom'))
                ->setPrenom($request -> request -> get('prenom'))
                ->setEmail($request -> request -> get('email'));
            $plainPassword=$request -> request -> get('password');
            $passwordEncode= $this->encoder->hashPassword($client,$plainPassword);
            $client->setPassword($passwordEncode);
            $em -> persist($client);
            $em -> flush();
            $_SESSION["Redirect"]="Panier";
            return $this->redirectToRoute("app_login");
        }
        return $this->render('register/client.register.html.twig', [
            'controller_name' => 'RegisterController',
        ]);
    }
}
