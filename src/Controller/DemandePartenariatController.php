<?php

namespace App\Controller;

use App\Entity\Partenariat;
use App\Repository\PartenaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PartenariatRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DemandePartenariatController extends AbstractController
{

    public function __construct(
        PartenariatRepository $partenariatRepository,
        EntityManagerInterface $em,
        PartenaireRepository $partRipo,
    )
    {
        $this->partenariatRepository = $partenariatRepository;
        $this->em = $em;
        $this->partRipo = $partRipo;
    }

    #[Route('/demande/partenariat', name: 'app_demande_partenariat')]
    public function index(): Response
    {
        return $this->render('demande_partenariat/index.html.twig', [
           'text'=>'Remplissez le formulaire pour effectuer une demande de partenariat',
           'partenaires' => $this->partRipo->findBy(['etat'=>True]),
        ]);
    }

    #[Route('/demande/partenariat/add', name: 'app_demande_partenariat_add', methods:["POST"])]
    public function add(Request $request){
        //dd($request);
        $data = $request->request;
        $partenariat = new Partenariat();
        $partenariat->setNom($data->get('nom'));
        $partenariat->setPrenom($data->get('prenom'));
        $partenariat->setMail($data->get('email'));
        $partenariat->setTelephone($data->get('full_number'));
        $partenariat->setEntreprise($data->get('entreprise'));
        $partenariat->setRequete($data->get('req'));
        $partenariat->setDate(new \DateTime());
        $partenariat->setEtat('DEMANDE');
        $this->em->persist($partenariat);
        $this->em->flush();
        
        return $this->render('demande_partenariat/index.html.twig', [
            'text'=>'Votre demande a été envoyée avec succès',
            'etat'=>'disabled',
            'partenaires' => $this->partRipo->findBy(['etat'=>True]),
        ]);
    }

}
