<?php

namespace App\Controller;

use App\Repository\PartenariatRepository;
use App\Service\Mail\ApiMailJet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminDemandePartenariatController extends AbstractController
{

    public function __construct(
        PartenariatRepository $partenariatRepository,
        EntityManagerInterface $em
    )
    {
        $this->partenariatRepository = $partenariatRepository;
        $this->em = $em;
        $this->partenariat = $partenariatRepository->findAll();
    }

    #[Route('/admin/demande/partenariat', name: 'app_admin_demande_partenariat')]
    public function index(): Response
    {
        return $this->render('admin/admin_demande_partenariat/index.html.twig', [
           'partenariats' => $this->partenariat,
        ]);
    }

    #[Route('/admin/demande/partenariat/{id}', name: 'admin_demande_partenariat_details', methods:['GET'])]
    public function details($id): Response{
        
        return $this->render('admin/admin_demande_partenariat/index.html.twig', [
            'selected' => $this->partenariatRepository->find($id),
            'partenariats' => $this->partenariat,
        ]);
    }

    #[Route('/admin/demande/partenariat/traitement', name: 'admin_demande_partenariat_traitement', methods:['POST'])]
    public function traitement(Request $request): Response{
        $data = $request->request->all();
        $partenariat = $this->partenariatRepository->find($data['id']);
        if($data['valide']){
            $partenariat->setEtat("VALIDE");
        } else {
            $partenariat->setEtat("REFUSE");
        }
        $partenariat->setReponse($data['reponse']);
        $partenariat->setDateReponse(new \DateTime());
        $mail = new ApiMailJet();
        $mail->send($partenariat->getMail(), $partenariat->getPrenom()." " .$partenariat->getNom(), "Demande de partenariat" ,$data['reponse']);
        $this->em->persist($partenariat);
        $this->em->flush();

        return $this->redirectToRoute('app_admin_demande_partenariat');
    }
    

}
