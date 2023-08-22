<?php

namespace App\Controller;

use DateTime;
use App\Entity\Abonne;
use App\Entity\Membre;
use App\Entity\Wallet;
use App\Entity\MembreBureau;
use App\Repository\MembreRepository;
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
         $membre->setEtat(true);
         $membre -> setRoles(["ROLE_MEMBRE"]);

         if ($data->get('bac')){
             $membre->setBac($data->get('bac'));
         }

         if ($data->get('secteur')){
             $membre->setSecteur($data->get('secteur'));
         }

         if ($data->get('entreprise')) {
             $membre->setEntreprise($data->get('entreprise'));
         }

         if ($data->get('etudes')) {
             $membre->setUniv($data->get('etudes'));
         }

        if ($data->get('diplomes')) {
            $membre->setDiplome($data->get('diplomes'));
        }

         if ($data->get('exp')){
             $membre->setExperience($data->get('exp'));
         }

         if ($data->get('profil')){
             $membre->setLink($data->get('profil'));
         }

         if ($data->get('bio')){
             $membre->setBio($data->get('bio'));
         }

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
         if($request->files->get('photo')){
            $img=$request->files->get("photo"); 
            $imageName=uniqid().'.'.$img->guessExtension(); 
            $img->move($this->getParameter("pp_directory"),$imageName);          
            $membre->setProfile($imageName);
         }
         
         
         $userPassword = date_format(new DateTime(),'Y-m-d-H-i');
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

    #[Route('/modif', name: 'admin_membre_modif')]
    public function modifMembre(Request $request, EntityManagerInterface $em, PostesBureauRepository $posteRipo,MembreRepository $membreRipo): Response
    {
        $data = $request->request;

        if($data->get("modif")){
            //dd($data);
             //creation du membre
            $membre = $membreRipo->find($data->get('id'));
            //dd($membre);
            $membre -> setNom($data->get('nom'));
            $membre -> setPrenom($data->get('prenom'));
            $membre -> setPromotion($data->get('promotion'));
            $membre -> setPays($data->get('pays'));
            $membre-> setVille($data->get('ville'));
            $membre -> setTelephone($data->get('full_number'));
            $membre -> setStatut(false);
            $membre -> setEmail($data->get('mail'));
            $membre -> setRoles(["ROLE_MEMBRE"]);

            if ($data->get('bac')){
                $membre->setBac($data->get('bac'));
            }

            if ($data->get('etat')){
                $membre->setEtat(true);
            } else {
                $membre->setEtat(false);
            }

            if ($data->get('secteur')){
                $membre->setSecteur($data->get('secteur'));
            }

            if ($data->get('entreprise')) {
                $membre->setEntreprise($data->get('entreprise'));
            }

            if ($data->get('etudes')) {
                $membre->setUniv($data->get('etudes'));
            }

            if ($data->get('diplomes')) {
                $membre->setDiplome($data->get('diplomes'));
            }

            if ($data->get('exp')){
                $membre->setExperience($data->get('exp'));
            }

            if ($data->get('profil')){
                $membre->setLink($data->get('profil'));
            }

            if ($data->get('bio')){
                $membre->setBio($data->get('bio'));
            }

            /* if ($data->get("role")==0) {
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
            } */
            if($request->files->get('photo')){
                $img=$request->files->get("photo"); 
                $imageName=uniqid().'.'.$img->guessExtension(); 
                $img->move($this->getParameter("pp_directory"),$imageName);          
                $membre->setProfile($imageName);
            }
            $em -> persist($membre);

            $em->flush();
            return $this->render('admin/admin_membre/modifier.membre.html.twig', [
                'roles'=>$posteRipo->findAll() 
            ]);
        }else{
            
            if($data->get("recherche")){
                $membre = $membreRipo->findOneBy(['email'=>$request->request->get('email')]);
                if ($membre==null) {
                    //dd($data);
                    $this->addFlash('error', 'Aucune membre n\'a cette adresse email dans la base de donnée');
                    return $this->render('admin/admin_membre/modifier.membre.html.twig', [
                        'roles'=>$posteRipo->findAll() 
                    ]);
                }else{
                    
                    return $this->render('admin/admin_membre/modifier.membre.html.twig', [
                        'roles'=>$posteRipo->findAll(),
                        'membreFound' => $membre 
                    ]);
                }
            }
            return $this->render('admin/admin_membre/modifier.membre.html.twig', [
                'roles'=>$posteRipo->findAll() 
            ]);
        }
        
    }
}
