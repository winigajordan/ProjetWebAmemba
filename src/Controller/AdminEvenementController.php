<?php

namespace App\Controller;

use DateTime;
use App\Entity\Evenement;
use App\Repository\CategorieEvenementRepository;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;

class AdminEvenementController extends AbstractController
{
    #[Route('/admin/evenement', name: 'app_admin_evenement')]
    public function index(CategorieEvenementRepository $cateRipo, EvenementRepository $evRipo): Response
    {

        return $this->render('admin/admin_evenement/index.html.twig', [
            'controller_name' => 'AdminEvenementController',
            'categories' => $cateRipo -> findAll(),
            'evenements' => $evRipo -> findAll()
        ]);
    }

    #[Route('/admin/evenement/add', name: 'admin_evenement_add')]
    public function add(Request $request, EntityManagerInterface $em, CategorieEvenementRepository $cateRipo)
    {
        $data = $request -> request;
        $date = ($data -> get('date'));
        $ev = new Evenement();
        $ev -> setCategorie( $cateRipo -> find(intval($data -> get('categorie'))));
        $ev->setDate(new DateTime($date));
        $ev -> setTitle($data->get('title'));
        $ev -> setContent($data->get('description'));
        $ev -> setStartAt(new DateTime($data->get('startAt')));
        $ev -> setEndAt(new DateTime($data->get('endAt')));
        $ev -> setCover($data->get('cover'));
        $em -> persist($ev);
        $em -> flush();
        return $this->redirectToRoute('app_admin_evenement');
    }

    #[Route('/admin/evenement/details/{id}', name: 'admin_evenement_details')]
    public function details($id,CategorieEvenementRepository $cateRipo, EvenementRepository $evRipo)
    {
        return $this->render('admin/admin_evenement/index.html.twig', [
            'controller_name' => 'AdminEvenementController',
            'categories' => $cateRipo -> findAll(),
            'evenements' => $evRipo -> findAll(),
            'selected' => $evRipo -> find(intval($id))
        ]);
    }

    #[Route('/admin/evenement/update/', name: 'admin_evenement_update')]
    public function mo(Request $request, CategorieEvenementRepository $cateRipo, EntityManagerInterface $em, EvenementRepository $evRipo)
    {
        $data = $request -> request;
        $ev = $evRipo->find(intval($data->get('id')));
        $ev -> setCategorie( $cateRipo -> find(intval($data -> get('categorie'))));
        $date = ($data -> get('date'));
        $ev->setDate(new DateTime($date));
        $ev -> setTitle($data->get('title'));
        $ev -> setContent($data->get('description'));
        $ev -> setStartAt(new DateTime($data->get('startAt')));
        $ev -> setEndAt(new DateTime($data->get('endAt')));
        $ev -> setCover($data->get('cover'));
        $em -> persist($ev);
        $em -> flush();
        return $this->redirectToRoute('app_admin_evenement');
    }
}
