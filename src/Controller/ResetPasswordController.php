<?php

namespace App\Controller;

use DateTime;
use App\Entity\ResetPassword;
use App\Service\Mail\ApiMailJet;
use App\Repository\MembreRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ResetPasswordRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetPasswordController extends AbstractController
{
    public function __construct(
        MembreRepository $membreRipo,
        EntityManagerInterface $em,
        ResetPasswordRepository $resetRipo,
        UserPasswordHasherInterface $hasher,
        )
    {
        $this->membreRipo = $membreRipo;
        $this->em = $em;
        $this->resetRipo = $resetRipo;
        $this->hasher = $hasher;
    }
    #[Route('/reset/password', name: 'app_reset_password')]
    public function index(): Response
    {
        return $this->render('reset_password/index.html.twig', [
         'titre'=>'index'
        ]);
    }

    #[Route('/reset', name: 'checkData')]
    public function checkData(Request $request)
    {
        $data = $request->request;
        if ($data->get('full_number')) {
            $membre = $this->membreRipo->findOneBy(['email'=>$data->get('email'), 'telephone'=>$data->get('full_number')]);
           
            if (!$membre) {
                $this->addFlash('warning', 'Votre mail et votre numéro de téléphone ne figurent pas dans la base de donnée');
                return $this->redirectToRoute('app_reset_password');
            } else {
                //création de l'objet Reset
                $reset = new ResetPassword();
                $reset->setTelephone($data->get('full_number'));
                $reset->setMail($data->get('email'));
                $reset->setCode('RST'.strval(random_int(0, 9999)));
                $reset->setEtat('DEMANDE');
                $reset->setMode('TEST');
                $reset->setDate(new DateTime());
                $this->em->persist($reset);

                //envoie de mail
                $mail = new ApiMailJet();
                $mail->send($reset->getMail(),"", "Reinitialisation de mot de passe", "Votre code de reinitialisation de mot de passe est $reset");
                $this->em->flush();
                $this->addFlash('warning', 'Un code de vérification vous a été envoyé à '.$data->get('email').'.');
                return $this->render('reset_password/index.html.twig', 
                    [
                        'titre'=>'code',
                        'reset'=>$reset
                    ]
                );
            }
        }

        if($data->get('code')){
            $reset= $this->resetRipo->findOneBy(['mail'=>$data->get('mail'), 'etat'=>'DEMANDE', 'code'=>$data->get('code')]);
            if (!$reset){
                $this->addFlash('warning', 'Code saisis invalide, veuillez le saisir à nouveau');
                return $this->render('reset_password/index.html.twig', 
                    [
                        'titre'=>'code',
                        'reset'=>$reset
                    ]
                );
            } else {
                $this->addFlash('warning', 'Saisir votre nouveau mot de passe');
                return $this->render('reset_password/index.html.twig', 
                    [
                        'titre'=>'password',
                        'code'=>$reset
                    ]
                );
            }
        }

        if($data->get('pwd1')){
            if ($data->get('pwd1')!=$data->get('pwd2')) {
                $reset= $this->resetRipo->findOneBy(['mail'=>$data->get('mail'), 'etat'=>'DEMANDE']);
                $this->addFlash('warning', 'Veuillez vérifier vos mots de passe');
                return $this->render('reset_password/index.html.twig', 
                    [
                        'titre'=>'password',
                        'code'=>$reset
                    ]
                );
            } else {
                $membre = $this->membreRipo->findOneBy(['email'=>$data->get('mail')]);
                $membre->setPassword($this->hasher->hashPassword($membre, $request->request->get('pwd1')));
                $this->em->persist($membre);
                $this->em->flush();
                $this->addFlash('warning', 'Votre mot de passe a été modifié avec succès, veuillez vous rediriger vers la page de connexion pour accéder à votre compte');
                return $this->render('reset_password/index.html.twig', [
                    'titre'=>''
                    
                ] );
            }

        }
       
    }

}
