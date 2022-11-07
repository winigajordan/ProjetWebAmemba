<?php

namespace App\Controller;

use DateTime;
use App\Entity\Abonne;
use App\Entity\Membre;
use App\Entity\Wallet;
use App\Entity\MembreBureau;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PostesBureauRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
#[Route('/admin/membre')]
class AdminMembreController extends AbstractController
{

    private UserPasswordHasherInterface $hasher;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    
    #[Route('', name: 'app_admin_membre')]
    public function index(PostesBureauRepository $postes): Response
    {
        return $this->render('admin/admin_membre/index.html.twig', [
            'roles'=>$postes->findAll() 
        ]);
    }

    #[Route('/add', name: 'admin_membre_add')]
    public function addMembre(Request $request, EntityManagerInterface $em, PostesBureauRepository $posteRipo): Response
    {
        $data = $request->request;
        //dd($data);
         //creation du memebre
         $membre = new Membre();
         $membre -> setNom($data->get('nom'));
         $membre -> setPrenom($data->get('prenom'));
         $membre -> setPromotion($data->get('promotion'));
         $membre -> setPays($data->get('pays'));
         $membre-> setVille($data->get('ville'));
         $membre -> setTelephone($data->get('full_number'));
         $membre -> setStatut(false);
         $membre -> setEmail($data->get('mail'));
         $membre -> setRoles(["ROLE_MEMBRE"]);

         if ($data->get("role")==0) {
            $membre->setRoleAmicale("MEMBRE");
         } else {
            $poste = $posteRipo->find($data->get("role"));
            $membre->setRoleAmicale($poste->getLibelle());
            $mb = (new MembreBureau)
            ->setFonction($poste)
            ->setNomComplet(strtoupper($membre->getPrenom().' '.$membre->getNom()))
           
            ->setEtat(true)
            ->setMembre($membre);
            $em->persist($mb);
         }

         $img=$request->files->get("photo"); 
         $imageName=uniqid().'.'.$img->guessExtension(); 
         $img->move($this->getParameter("pp_directory"),$imageName);          
         $membre->setProfile($imageName);
         
         $userPassword = date_format(new DateTime(),'Y-m-d-H-i-s');
         $pwd = $this->hasher->hashPassword($membre, $userPassword);
         $membre -> setPassword($pwd);
         $em -> persist($membre);

         //ajout à la newsletter
        $abonne = new Abonne();
        $abonne -> setNom($membre->getNom());
        $abonne -> setPrenom($membre->getPrenom());
        $abonne -> setMail($membre->getEmail());
        $abonne -> setStatus(true);
        $em -> persist($abonne);
        
        //creation de wallet
        $wallet = new Wallet();
        $wallet -> setSolde(0);
        $wallet -> setMembre($membre);
        $em -> persist($wallet);

        $em->flush();
        return $this->redirectToRoute('app_admin_membre');
    }
}
