<?php

namespace App\Controller;

use App\Entity\Abonne;
use DateTime;
use App\Entity\Membre;
use App\Entity\Wallet;
use App\Repository\MembreRepository;
use App\Repository\DemandeRepository;

use App\Service\Mail\ApiMailJet;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DemandeController extends AbstractController
{
    private UserPasswordHasherInterface $hasher;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    #[
        Route('/admin/demande', name: 'demande'),
        IsGranted("ROLE_ADMIN")
    ]
    public function index(DemandeRepository $dmd): Response
    {
        $demandes = $dmd -> findBy(["etat"=>"EN COURS"]);
        return $this->render('./demande/index.html.twig', [
            'controller_name' => 'DemandeController',
            'demandes' => $demandes,
            'nombreDemandes'=>count($demandes)
        ]);
    }

    #[
        Route('/admin/demande/details/{id}', name: 'details_demande'),
        IsGranted("ROLE_ADMIN")
    ]
    public function details($id, DemandeRepository $dmd) : Response
    {
        $demandes = $dmd -> findBy(["etat"=>"EN COURS"]);
        $selected = $dmd -> find($id);
        if ($selected == null or $selected->getEtat()!="EN COURS") {
            return $this->redirectToRoute('demande');
        }
        return $this->render('./demande/index.html.twig',[
            'controller_name' => 'DemandeController',
            'demandes' => $demandes,
            'selected' => $selected,
            'nombreDemandes'=>count($demandes)
        ]);
    }


    #[Route('/admin/demande/valider/{id}', name: 'valider_demande'), IsGranted("ROLE_ADMIN")]
    public function validationDemande($id,  DemandeRepository $dmd, MembreRepository $membreRipo, EntityManagerInterface $manager): Response
    {
        $selected = $dmd -> find($id);
        $userMail = $selected -> getMail();
        

        //creation du memebre
        $membre = new Membre();
        $membre -> setNom($selected -> getNom());
        $membre -> setPrenom($selected -> getPrenom());
        $membre -> setPromotion($selected -> getPromotion());
        $membre -> setPays($selected -> getPays());
        $membre-> setVille($selected -> getVille());
        $membre -> setTelephone($selected->getTelephone());
        $membre -> setStatut(false);
        $membre -> setEmail($selected -> getMail());
        $membre -> setRoles(["ROLE_MEMBRE"]);
        $userPassword = date_format(new DateTime(),'Y-m-d-H-i-s');
        $pwd = $this->hasher->hashPassword($membre, $userPassword);
        $membre -> setPassword($pwd);
        $selected -> setEtat('VALIDE');
        $manager -> persist($membre);
        $manager -> persist($selected);

        //ajout à la newsletter
        $abonne = new Abonne();
        $abonne -> setNom($membre->getNom());
        $abonne -> setPrenom($membre->getPrenom());
        $abonne -> setMail($membre->getEmail());
        $abonne -> setStatus(true);
        $manager -> persist($abonne);
        
        //creation de wallet
        $wallet = new Wallet();
        $wallet -> setSolde(0);
        $wallet -> setMembre($membre);
        $manager -> persist($wallet);

        //envoie de mail
        $content = "Votre login est $userMail et votre mot de passe est $userPassword";
        $mail = new ApiMailJet();
        $mail -> send($selected-> getMail(), "", "Demande accepté", $content);

        $manager -> flush();
        return $this->redirectToRoute('demande');
    }


    #[Route('/admin/demande/annuler/{id}', name: 'annuler_demande'), IsGranted("ROLE_ADMIN")]
    public function annulationDemande($id,  DemandeRepository $dmd, EntityManagerInterface $manager) : Response
    {
        $selected = $dmd -> find($id);
        $selected -> setEtat('REFUSE');
        $content = "Suite à des vérifications, nous ne sommes pas en mesure de valider la création de votre compte";
        $mail = new ApiMailJet();
        $mail -> send($selected-> getMail(), "", "Demande refusé", $content);
        $manager -> persist($selected);
        $manager -> flush();
        return $this->redirectToRoute('demande');
    }

}
