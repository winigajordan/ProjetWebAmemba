<?php

namespace App\Controller;

use App\Entity\Letter;
use App\Service\Mail\ApiMailJet;
use App\Repository\AbonneRepository;
use App\Repository\LetterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NewsletterController extends AbstractController
{
    #[Route('/newsletter', name: 'app_newsletter'), IsGranted("ROLE_ADMIN")]
    public function index( LetterRepository $letterRepo): Response
    {
        $letters = $letterRepo->findAll();
        return $this->render('newsletter/index.html.twig', [
            'controller_name' => 'NewsletterController',
            'letters' => $letters
        ]);
    }

    #[Route('/newsletter/add', name:'app_newletter_add'), IsGranted("ROLE_ADMIN")]
    public function add(Request $request,
        AbonneRepository $aboRepo,
        EntityManagerInterface $em){
        $letter = new Letter();
        $letter->setContent($request->request->get('contenu'));
        $letter->setTitre($request->request->get('titre'));
        $em->persist($letter);
        $em->flush();
        $abonnes = $aboRepo->findBy(['status'=>1]);
        foreach ($abonnes as $abo) {
            $mail = new ApiMailJet();
            $mail -> news($abo->getMail(), $abo->getPrenom()." " .$abo->getNom(), $request->request->get('titre'), $request->request->get('contenu'));
        }
        return $this->redirectToRoute('app_newsletter');
    }

    #[Route('/newsletter/details/{id}', name: 'app_newsletter_details'), IsGranted("ROLE_ADMIN")]
    public function letterDetails(
        $id,
        LetterRepository $letterRepo
    ):Response{
        $letter = $letterRepo->find($id);
        $paragraphes =explode("\n\r",$letter->getContent());
        return $this->render('newsletter/newsletter.details.html.twig', [
            'controller_name' => 'NewsletterController',
            'letter' => $letter,
            'paragraphes' => $paragraphes
        ]);
    }
}
