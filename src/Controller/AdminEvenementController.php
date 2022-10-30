<?php

namespace App\Controller;

use DateTime;
use App\Entity\Image;
use App\Entity\Evenement;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategorieEvenementRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;

class AdminEvenementController extends AbstractController
{
    #[Route('/admin/evenement', name: 'app_admin_evenement'), IsGranted("ROLE_ADMIN")]
    public function index(CategorieEvenementRepository $cateRipo, EvenementRepository $evRipo): Response
    {

        return $this->render('admin/admin_evenement/index.html.twig', [
            'controller_name' => 'AdminEvenementController',
            'categories' => $cateRipo -> findAll(),
            'evenements' => $evRipo -> findAll()
        ]);
    }

    #[Route('/admin/evenement/add', name: 'admin_evenement_add'), IsGranted("ROLE_ADMIN")]
    public function add(Request $request, EntityManagerInterface $em, CategorieEvenementRepository $cateRipo)
    {
        
        $data = $request -> request;
        if (strlen($data->get('description'))==0){
            $this->addFlash('error', 'Veuillez saisir une valeur dans le champ description');
            return $this->redirectToRoute('app_admin_evenement');
        }
        $date = ($data -> get('date'));
        $ev = new Evenement();
        $ev -> setCategorie( $cateRipo -> find(intval($data -> get('categorie'))));
        $ev->setDate(new DateTime($date));
        $ev -> setTitle($data->get('title'));
        $ev -> setContent($data->get('description'));
        $ev -> setStartAt(new DateTime($data->get('startAt')));
        $ev -> setEndAt(new DateTime($data->get('endAt')));
        $image=new Image(); 

        $img=$request->files->get("image"); 
        $imageName=uniqid().'.'.$img->guessExtension(); 
        $img->move($this->getParameter("evenement_directory"),$imageName);          
        $image->setPath($imageName);
        
        $ev->addImage($image);
        $em -> persist($image);
        $em -> persist($ev);
        $em -> flush();
        return $this->redirectToRoute('app_admin_evenement');
    }

    #[Route('/admin/evenement/details/{id}', name: 'admin_evenement_details'), IsGranted("ROLE_ADMIN")]
    public function details($id,CategorieEvenementRepository $cateRipo, EvenementRepository $evRipo)
    {
        return $this->render('admin/admin_evenement/index.html.twig', [
            'controller_name' => 'AdminEvenementController',
            'categories' => $cateRipo -> findAll(),
            'evenements' => $evRipo -> findAll(),
            'selected' => $evRipo -> find(intval($id))
        ]);
    }

    #[Route('/admin/evenement/update/', name: 'admin_evenement_update'), IsGranted("ROLE_ADMIN")]
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
        if($request->files->get("image")){
            $image=new Image(); 
            $img=$request->files->get("image"); 
            $imageName=uniqid().'.'.$img->guessExtension(); 
            $img->move($this->getParameter("evenement_directory"),$imageName);          
            $image->setPath($imageName);
            unlink($this->getParameter("evenement_directory")."/".$ev->getImages()[0]->getPath());
            $em->remove($ev->getImages()[0]);
            $ev->removeImage($ev->getImages()[0]);
            $ev->addImage($image);
            $em->persist($image);
        }
        
        $em -> persist($ev);
        $em -> flush();
        return $this->redirectToRoute('app_admin_evenement');
    }
}
