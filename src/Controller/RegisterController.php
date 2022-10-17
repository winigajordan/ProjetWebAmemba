<?php

namespace App\Controller;

use Mail;
use DateTime;
use App\Entity\Client;
use App\Entity\Demande;
use App\Repository\MembreRepository;
use App\Repository\UserRepository;
use App\Service\Mail\ApiMailJet;
use App\Service\Mail\ApiMailVerification;
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
    public function __construct(UserPasswordHasherInterface $encoder,
    ApiMailVerification $verif){
        $this->encoder=$encoder;
        $this->verif=$verif;
    }

    #[Route('/register', name: 'app_register')]
    public function index(): Response
    {
        return $this->render('register/index.html.twig', [
            'txtRegister' => 'Remplissez le formulaire pour une demande de création de compte',
        ]);
    }

    #[Route('/register/send', name: 'add_demande', methods: ['POST'])]
    public function aaddDemande(Request $request, MembreRepository $membreRipo, EntityManagerInterface $em): Response {
        $user = $membreRipo->findOneBy(['email'=>$request->request->get('email')]);
        $user2 = $membreRipo->findOneBy(['telephone'=>$request->request->get('full_number')]);
        if ($user or $user2) {
            $this->addFlash('info', 'Le mail et ou le numéro de téléphone que vous avez saisie figurent déjà dans la base de donnée');
            return $this->redirectToRoute('app_register');;
        } else {
            $verif = new ApiMailVerification();
            $result = $verif -> test($request->request->get('email'));
            if($result['deliverability'] == 'DELIVERABLE'){

                $demande = new Demande();
                $demande -> setNom($request -> request -> get('nom'));
                $demande -> setPrenom($request -> request -> get('prenom'));
                $demande -> setMail($request -> request -> get('email'));
                $demande -> setPromotion($request -> request -> get('promotion'));
                $demande -> setPays($request -> request -> get('pays'));
                $demande -> setVille($request -> request -> get('ville'));
                $demande -> setTelephone($request -> request -> get('full_number'));
                $demande -> setEtat('EN COURS');
                $demande -> setDate(new DateTime());  
                $em -> persist($demande);
                $em -> flush();
                $nom = $demande -> getNom()." ".$demande -> getPrenom();
                $date = date_format($demande -> getDate(),'Y/m/d-H:i:s');
                $content = "Une demande d'adhésion vien d'être effectuée par $nom \n Date et heure : $date \n Veuillez vous connecter pour traiter la demande";
                $mail = new ApiMailJet();
                $mail -> send("contact@mariamaba-alumni.com", "", "Demande d'adhésion", $content);
                $this->addFlash('info', 'Votre demande a été envoyée');
                return $this->redirectToRoute('app_register');
            }
            else {
                return $this->render('register/index.html.twig', [
                    'txtRegister' => 'Veuillez verifier votre adresse mail et remplissez à nouveau le formulaire',
                ]);
            }
        }
       
    }

    #[Route('/register/client', name: 'add_client')]
    public function addClient(Request $request, EntityManagerInterface $em,
    SessionInterface $session): Response {
        if (!empty($_POST)){
            if ($this->verif->test($request->request->get('email'))['deliverability'] == 'DELIVERABLE'){
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
            } else {
                return $this->render('register/client.register.html.twig', [
                    'txtRegister' => 'Veuillez verifier votre adresse mail et remplissez à nouveau le formulaire',
                ]);
            }
        }
        return $this->render('register/client.register.html.twig', [
            'txtRegister' => 'Formulaire de création de compte client',
        ]);
    }
}
