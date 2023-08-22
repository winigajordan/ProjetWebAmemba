<?php

namespace App\Controller;

use App\Repository\FooterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FooterController extends AbstractController
{
    #[Route('/footer', name: 'app_footer')]
    public function index(FooterRepository $footRepo,EntityManagerInterface $em, Request $request): Response
    {
        $foot = $footRepo->find(1);
        if(!empty($_POST)){
            $foot->setMail($request->request->get("mail"));
            $foot->setInstagram($request->request->get("instagram"));
            $foot->setFacebook($request->request->get("facebook"));
            $foot->setTwitter($request->request->get("twitter"));
            $foot->setWhatsapp($request->request->get("whatsapp"));
            $em->persist($foot);
            $em->flush();
        }
        return $this->render('footer/index.html.twig', [
            'controller_name' => 'FooterController',
            'foot' => $foot
        ]);
    }
}
