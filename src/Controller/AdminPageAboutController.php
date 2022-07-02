<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\PageAbout;
use App\Entity\Realisation;
use App\Repository\PageAboutRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RealisationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminPageAboutController extends AbstractController
{

    public function __construct(
        PageAboutRepository $aboutRipo,
        RealisationRepository $realRipo,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher,
    ){
        $this->aboutRipo = $aboutRipo;
        $this->realRipo = $realRipo;
        $this->em = $em;
        $this->page = $this->aboutRipo->find(1);
        $this->hasher = $hasher;
    }

    #[Route('/admin/page/about', name: 'app_admin_page_about'), IsGranted("ROLE_MEMBRE")]
    public function index(): Response
    {
        return $this->render('admin/admin_page_about/index.html.twig', [
            'page'=>$this->page,
            'reals'=>$this->realRipo->findBy(['etat'=>"VALIDE"])
        ]);
    }

    #[Route('/admin/page/about/section1/update', name: 'page_about_section1'), IsGranted("ROLE_MEMBRE")]
    public function section1(Request $request){
        $data = $request->request;
        $page = $this->page;
        $page->setMissionTitre($data->get('missionTitre'));
        $page->setMissionText($data->get('missionTexte'));
        $this->em->persist($page);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_page_about');
    }

    #[Route('/admin/page/about/section2/update', name: 'page_about_section2'), IsGranted("ROLE_MEMBRE")]
    public function section2(Request $request){
       
        $real = new Realisation();
        $real->setTitre($request->request->get('titre'));
        $real->setDescription($request->request->get('description'));
        $real->setEtat("VALIDE");
        $img=$request->files->get("img"); 
        $imageName=uniqid().'.'.$img->guessExtension(); 
        $img->move($this->getParameter("pages_directory"),$imageName);
        $real->setImage($imageName);

        $this->em->persist($real);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_page_about');
    
    }

    #[Route('/admin/page/about/section2/edit/{id}', name: 'page_about_section2_edit'), IsGranted("ROLE_MEMBRE")]
    public function section2edit($id){
       
        $real = $this->realRipo->find(intval($id));
        if($real==null){
            return $this->redirectToRoute('app_admin_page_about');
        } else {
        $real->setEtat('ARCHIVE');
        $this->em->persist($real);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_page_about');
        }
    }

    #[Route('/admin/page/about/section3/update', name: 'page_about_section3'), IsGranted("ROLE_MEMBRE")]
    public function section3(Request $request){
        $page = $this->page;
        $page ->setMotTitre($request->request->get('motTitre'));
        $page -> setMotContenu($request->request->get('motContenue'));
        $this->em->persist($page);

        /* $admin = new Admin();
        $admin -> setNom('Winiga');
        $admin -> setRoles(['ROLE_ADMIN']);
        $admin -> setEmail('admin@admin.com');
        $admin -> setPrenom('Jordan');
        $admin -> setPassword($this->hasher->hashPassword($admin, '1234'));
        $this->em->persist($admin); */
        
        $this->em->flush();
        return $this->redirectToRoute('app_admin_page_about');

    }
}
