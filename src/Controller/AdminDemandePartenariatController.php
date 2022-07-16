<?php

namespace App\Controller;

use App\Service\Mail\ApiMailJet;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PartenariatRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    #[Route('/admin/demande/partenariat', name: 'app_admin_demande_partenariat'),  IsGranted("ROLE_ADMIN")]
    public function index(): Response
    {
        return $this->render('admin/admin_demande_partenariat/index.html.twig', [
           'partenariats' => $this->partenariat,
        ]);
    }

    #[Route('/admin/demande/partenariat/{id}', name: 'admin_demande_partenariat_details', methods:['GET']), IsGranted("ROLE_ADMIN")]
    public function details($id): Response{
        
        return $this->render('admin/admin_demande_partenariat/index.html.twig', [
            'selected' => $this->partenariatRepository->find($id),
            'partenariats' => $this->partenariat,
        ]);
    }

    #[Route('/admin/demande/partenariat/traitement', name: 'admin_demande_partenariat_traitement', methods:['POST']), IsGranted("ROLE_ADMIN")]
    public function traitement(Request $request): Response{
        $data = $request->request;
        $partenariat = $this->partenariatRepository->find($data->get('id'));
        if($data->get('valide')){
            $partenariat->setEtat("VALIDE");
        } else {
            $partenariat->setEtat("REFUSE");
        }
        $partenariat->setReponse($data->get('reponse'));
        $partenariat->setDateReponse(new \DateTime());
        $mail = new ApiMailJet();
        $mail->part($partenariat->getMail(), $partenariat->getPrenom()." " .$partenariat->getNom(),$data->get('reponse'));
        //dd($partenariat);
        $this->em->persist($partenariat);
        $this->em->flush();

        return $this->redirectToRoute('app_admin_demande_partenariat');
    }
    

}
