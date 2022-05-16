<?php

namespace App\Controller;

use App\Entity\Membre;
use App\Repository\DemandeRepository;
use App\Repository\MembreRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DemandeController extends AbstractController
{
    private UserPasswordHasherInterface $hasher;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    #[Route('/admin/demande', name: 'demande')]
    public function index(DemandeRepository $dmd): Response
    {

        $demandes = $dmd -> findBy(["etat"=>"EN COURS"]);
        return $this->render('./demande/index.html.twig', [
            'controller_name' => 'DemandeController',
            'demandes' => $demandes,
            'nombreDemandes'=>count($demandes)
        ]);
    }

    #[Route('/admin/demande/details/{id}', name: 'details_demande')]
    public function details($id, DemandeRepository $dmd) : Response
    {
        $demandes = $dmd -> findBy(["etat"=>"EN COURS"]);
        $selected = $dmd -> find($id);
        return $this->render('./demande/index.html.twig',[
            'controller_name' => 'DemandeController',
            'demandes' => $demandes,
            'selected' => $selected,
            'nombreDemandes'=>count($demandes)
        ]);
    }


    #[Route('/admin/demande/valider/{id}', name: 'valider_demande')]
    public function validationDemande($id,  DemandeRepository $dmd, MembreRepository $membreRipo, EntityManagerInterface $manager): Response
    {
        $selected = $dmd -> find($id);
        $userMail = $selected -> getMail();
        //dd($selected);
        
        $userPassword = date_format(new DateTime(),'Y/m/d-H:i:s');
        
        $content = "Votre login est $userMail et votre mot de passe est $userPassword";
        $mail = new Mail();
        $mail -> send($selected-> getMail(), "", "Demande accepté", $content);
        $membre = new Membre();
        $membre -> setNom($selected -> getNom());
        $membre -> setPrenom($selected -> getPrenom());
        $membre -> setPromotion($selected -> getPromotion());
        $membre -> setPays($selected -> getPays());
        $membre-> setVille($selected -> getVille());
        $membre -> setTelephone('');
        $membre -> setStatut(false);
        $membre -> setEmail($selected -> getMail());
        
        $password = "1234";
        $membre -> setRoles(["ROLE_MEMBRE"]);
        $pwd = $this->hasher->hashPassword($membre, $password);
        $membre -> setPassword($pwd);
        $selected -> setEtat('VALIDE');
        $manager -> persist($membre);
        $manager -> persist($selected);
        $manager -> flush();
        return $this->redirectToRoute('demande');
    }


    #[Route('/admin/demande/annuler/{id}', name: 'annuler_demande')]
    public function annulationDemande($id,  DemandeRepository $dmd, EntityManagerInterface $manager) : Response
    {
        $selected = $dmd -> find($id);
        $selected -> setEtat('REFUSE');
        $content = "Suite à des vérifications, nous ne sommes pas en mesure de valider la création de votre compte";
        $mail = new Mail();
        $mail -> send($selected-> getMail(), "", "Demande refusé", $content);
        $manager -> persist($selected);
        $manager -> flush();
        return $this->redirectToRoute('demande');
    }

}
