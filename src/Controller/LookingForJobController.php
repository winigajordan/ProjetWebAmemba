<?php

namespace App\Controller;

use App\Repository\MembreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LookingForJobController extends AbstractController
{

    public function __construct(
        MembreRepository $membreRipo,
        EntityManagerInterface $em
    )
    {
        $this->membreRipo = $membreRipo;
        $this->em = $em;
    }

    #[Route('/looking/for/job', name: 'app_looking_for_job')]
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/LookingForJobController.php',
        ]);
    }

    #[Route('/looking/for/job/activate', name: 'app_looking_for_job_activate'), IsGranted("ROLE_MEMBRE")]
    public function acive(): Response
    {
        $userConnected = $this->membreRipo->find($this->getUser()->getId());
        $userConnected ->setStatut(true);
        $this->em->persist($userConnected);
        $this->em->flush();

        return $this->redirectToRoute('app_accueil');
    }

    #[Route('/looking/for/job/disable', name: 'app_looking_for_job_disable'), IsGranted("ROLE_MEMBRE")]
    public function desactive(): Response
    {
        $userConnected = $this->membreRipo->find($this->getUser()->getId());
        $userConnected ->setStatut(false);
        $this->em->persist($userConnected);
        $this->em->flush();
        return $this->redirectToRoute('app_accueil');
    }

    
}