<?php

namespace App\Controller;

use DateTime;
use App\Entity\OffreEmplois;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OffreEmploisRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminEmploisController extends AbstractController
{

    public function __construct(
        OffreEmploisRepository $emploisRipo, 
        EntityManagerInterface $em,
        )
    {
        $this -> emploisRipo = $emploisRipo;
        $this -> em = $em;
    }

    #[Route('/admin/emplois', name: 'app_admin_emplois'), IsGranted("ROLE_ADMIN")]
    public function index(): Response
    {
        return $this->render('admin/admin_emplois/index.html.twig', [
            'controller_name' => 'AdminEmploisController',
            'emplois'=> $this->emploisRipo->findAll(),
            'today' => date_format(new DateTime(),'Y-m-d')
        ]);
    }

    #[Route('/admin/emplois/add', name: 'app_admin_emplois_add'), IsGranted("ROLE_ADMIN")]
    public function add(Request $request): Response
    {
        $offre = new OffreEmplois();
        $offre -> setTitre($request->request->get('titre'));
        $offre -> setSlug($this->slg($request->request->get('titre')));
        $offre -> setDomaine($request->request->get('domaine'));
        $offre -> setDescription($request->request->get('description'));
        $offre -> setCreateAt(new DateTime());
        $offre -> setEndAt(new DateTime($request->request->get('date')));
        $offre -> setEtat($request->request->get('etat'));
        $this->em->persist($offre);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_emplois');
    }

    public function slg($slug){
        return str_replace(" ","-",strtolower($slug));
    }

    #[Route('/admin/emplois/details/{slug}', name: 'admin_emplois_details'), IsGranted("ROLE_ADMIN")]
    public function details($slug) : Response{
        return $this->render('admin/admin_emplois/index.html.twig', [
            'controller_name' => 'AdminEmploisController',
            'emplois'=> $this->emploisRipo->findAll(),
            'today' => date_format(new DateTime(),'Y-m-d'),
            'selected' => $this->emploisRipo->findOneBy(['slug'=>$slug])
        ]);
    }

    #[Route('/admin/emplois/update', name: 'app_admin_emplois_update'), IsGranted("ROLE_ADMIN")]
    public function update(Request $request) {
        $data = $request->request;
        $offre = $this->emploisRipo->find(intval($data->get('id')));
        $offre -> setTitre($request->request->get('titre'));
        $offre -> setSlug($this->slg($request->request->get('titre')));
        $offre -> setDomaine($request->request->get('domaine'));
        $offre -> setDescription($request->request->get('description'));
        $offre -> setEndAt(new DateTime($request->request->get('date')));
        $offre -> setEtat($request->request->get('etat'));
        $this->em->persist($offre);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_emplois');
    }

}
