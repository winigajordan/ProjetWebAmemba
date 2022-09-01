<?php

namespace App\Controller;

use App\Entity\Abonne;
use App\Repository\AbonneRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Mail\ApiMailVerification;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AbonneController extends AbstractController
{
    
    #[Route('/abonne', name: 'app_abonne')]
    public function index(
        EntityManagerInterface $em,
        AbonneRepository $aboRepo,
        Request $request
    ): Response
    {
        if(!empty($_POST)){
            $abonne = $aboRepo->findOneBy(['mail'=>$request->request->get('mail')]);
            if($abonne){
                $this->addFlash('info','Cette adresse mail est dejà inscrite à la newsletter');
            }else{
                $verif = new ApiMailVerification();
                $result = $verif -> test($request->request->get('mail'));
                if($result['deliverability'] == 'DELIVERABLE'){
                    $abo = new Abonne();
                    $abo-> setNom($request->request->get('nom'))
                        -> setPrenom($request->request->get('prenom'))
                        -> setMail($request->request->get('mail'));
                    $em-> persist($abo);
                    $em->flush();
                }else{
                    $this->addFlash('info','Cette adresse mail est invalide'); 
                }
            }
        }
        return $this->redirectToRoute('app_accueil');
    }


}
