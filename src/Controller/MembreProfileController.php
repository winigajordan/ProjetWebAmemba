<?php

namespace App\Controller;

use App\Entity\Membre;
use App\Repository\MembreRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Mail\ApiMailVerification;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MembreProfileController extends AbstractController
{
    public function __construct(
        MembreRepository $membreRepository,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em,
        ApiMailVerification $verif
    ){
        $this->membreRepository = $membreRepository;
        $this->hasher = $hasher;
        $this->em = $em;
        $this->verif = $verif;
        
    }


    #[Route('/membre/profile', name: 'app_membre_profile'), IsGranted("ROLE_MEMBRE")]
    public function index(): Response
    {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('membre_profile/index.html.twig', [
            'membre' => $this->membreRepository->find($this->getUser()->getId()),
        ]);
    }

    #[Route('/membre/profile/update', name: 'app_membre_profile_edit', methods: ['POST']), IsGranted("ROLE_MEMBRE")]
    public function update(Request $request): Response
    {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('app_login');
        }
        $membre = $this->membreRepository->find($this->getUser()->getId());
        
        $membre->setBio($request->request->get('bio'));
        $membre->setRoleAmicale($request->request->get('role'));
        $membre->setNom($request->request->get('nom'));
        $membre->setPrenom($request->request->get('prenom'));
        $membre->setPays($request->request->get('pays'));
        $membre->setVille($request->request->get('ville'));
        $membre->setPromotion($request->request->get('promotion'));
        $membre->setEmail($request->request->get('email'));
        $membre->setProfession($request->request->get('profession'));
        $membre->setBac($request->request->get('bac'));
        $membre->setEntreprise($request->request->get('entreprise'));
        $membre->setSecteur($request->request->get('secteur'));
        $membre->setUniv($request->request->get('univ'));
        $membre->setDiplome($request->request->get('diplome'));
        $membre->setExperience($request->request->get('experience'));
        $membre->setLink($request->request->get('link'));
        
        //telephone
        if (!strpos($request->request->get('full_number'), "+")) {
           $membre->setTelephone($request->request->get('full_number'));
        }

        //mail
        $result = $this->verif-> test($request->request->get('email'));
        if($result['deliverability'] == 'DELIVERABLE'){
            $membre->setEmail($request->request->get('email'));
        }
        else{
            $this->addFlash('danger', 'Email non valide');
            return $this->render('membre_profile/index.html.twig', [
                    'membre' => $membre,
                ]);
        }
        //mot de passe
        if(!empty($request->request->get('pwd1'))){
            if ($request->request->get('pwd1') == $request->request->get('pwd2')){
                $membre->setPassword($this->hasher->hashPassword($membre, $request->request->get('pwd1')));
            }
            else{
                $this->addFlash('danger', 'Les mots de passe ne correspondent pas');
                return $this->render('membre_profile/index.html.twig', [
                    'membre' => $membre,
                ]);
            }
        }

        //photo de profil
        if(!empty($request->files->get('image'))){
            $img = $request->files->get('image');
            $imageName = uniqid().'.'.$img->guessExtension();
            $img->move(  $this->getParameter('pp_directory'),$imageName );
            $membre->setProfile($imageName);
        }


        //cv
        if ($request->files->get('cv')){
            if(!empty($request->files->get('cv')))
            {
                $cv = $request->files->get('cv');
                $cvName = uniqid().'.'.$cv->guessExtension();
                $cv->move($this->getParameter('cv_directory'),$cvName);
                $membre->setCv($cvName);
            }
        }
        
        $this->em->persist($membre);
        $this->em->flush();

        $this->addFlash('success', 'Votre profil a été mis à jour');
        return $this->redirectToRoute('app_membre_profile');
    }
}
