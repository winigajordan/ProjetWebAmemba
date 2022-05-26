<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Repository\EvenementRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AgendaDetailsController extends AbstractController
{
    #[Route('/agenda/details/{id}', name: 'app_agenda_details')]
    public function index($id, EvenementRepository $evRipo): Response
    {
        return $this->render('agenda_details/index.html.twig', [
            'controller_name' => 'AgendaDetailsController',
            'evenement' => $evRipo-> find(intval($id)),
            'commentaires' => $evRipo -> find(intval($id)) ->getCommentaires(),

        ]);
    }

    #[Route('/agenda/details/commentaire/add', name: 'app_agenda_commentaire')]
    public function commenter(Request $request, UserRepository $userRipo, EvenementRepository $evRipo, EntityManagerInterface $em){
        $data = $request->request;
        $cmt = new Commentaire();
        if ($this->getUser()) {
        $user = $userRipo -> find($this->getUser()->getId());
        $cmt -> setDate(new DateTime());
        $cmt -> setContent($data->get('content'));
        $cmt -> setEvenement($evRipo -> find(intval($data->get('id'))));
        $cmt -> setUser($user);
        $em -> persist($cmt);
        $em -> flush();
        return $this->redirectToRoute('app_agenda_details', ['id' => $data->get('id')]);
        } else {
            return $this-> redirectToRoute('app_login');
        }
    }
}
