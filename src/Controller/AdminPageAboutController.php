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
    private PageAboutRepository $aboutRipo;
    private RealisationRepository $realRipo;
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $hasher;
    private PageAbout $page;
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

    #[Route('/admin/page/about', name: 'app_admin_page_about'), IsGranted("ROLE_ADMIN")]
    public function index(): Response
    {
        return $this->render('admin/admin_page_about/index.html.twig', [
            'page'=>$this->page,
            'reals'=>$this->realRipo->findAll()
        ]);
    }

    #[Route('/admin/page/about/section1/update', name: 'page_about_section1'), IsGranted("ROLE_ADMIN")]
    public function section1(Request $request){
        $files = $request->files;
        $data = $request->request;
        $page = $this->page;
        $page->setMissionTitre($data->get('missionTitre'));
        $page->setMissionText($data->get('missionTexte'));
        if(!empty($files->get("img1"))){
            $img=$files->get("img1"); 
            $imageName=uniqid().'.'.$img->guessExtension(); 
            $img->move($this->getParameter("pages_directory"),$imageName);
            $page->setMissionPath($imageName);
        } 
        $this->em->persist($page);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_page_about');
    }

    #[Route('/admin/page/about/section2/update', name: 'page_about_section2'), IsGranted("ROLE_ADMIN")]
    public function section2(Request $request){
       
        $real = new Realisation();
        $real->setTitre($request->request->get('titre'));
        //$real->setDescription('aa');
        $real->setDescription($request->request->get('description'));
        $real->setMiniDescription($request->request->get('min-description'));
        $real->setEtat("VALIDE");
        $img=$request->files->get("img"); 
        $imageName=uniqid().'.'.$img->guessExtension(); 
        $img->move($this->getParameter("pages_directory"),$imageName);
        $real->setImage($imageName);
        $this->em->persist($real);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_page_about');
    
    }

    #[Route('/admin/realisation/{id}', name: 'app_realisation_details')]  
    public function realisationDetails($id):Response{
        $real = $this->realRipo->find($id);
        return $this->render('about/realisation.details.html.twig', [
            "real"=>$real
        ]); 
    }

    #[Route('/admin/realisation/delete/{id}', name: 'app_delete_realisation')]  
    public function deleteRealisation($id):Response{
        $real = $this->realRipo->find($id);
        $this->em->remove($real);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_page_about');
    }

    #[Route('/admin/realisation/update/{id}', name: 'app_realisation_update')]  
    public function updateRealisation($id,Request $request):Response{
        $real = $this->realRipo->find($id);
        if(!empty($_POST)){
            $real->setTitre($request->request->get('titre'));
            //$real->setDescription('aa');
            $real->setDescription($request->request->get('description'));
            $real->setMiniDescription($request->request->get('min-description'));
            $real->setEtat("VALIDE");
            if($request->files->get("img")){
                $img=$request->files->get("img"); 
                $imageName=uniqid().'.'.$img->guessExtension(); 
                $img->move($this->getParameter("pages_directory"),$imageName);
                $real->setImage($imageName);
            }
            $this->em->persist($real);
            $this->em->flush();
            return $this->redirectToRoute('app_admin_page_about');
        }else{
            return $this->render('admin/admin_page_about/index.html.twig', [
                'page'=>$this->page,
                'reals'=>$this->realRipo->findAll(),
                'real' => $real
            ]); 
        }
        
    }

    #[Route('/admin/page/about/section2/edit/{id}', name: 'page_about_section2_edit'), IsGranted("ROLE_ADMIN")]
    public function section2edit($id){
       
        $real = $this->realRipo->find(intval($id));
        if($real==null){
            return $this->redirectToRoute('app_admin_page_about');
        } else {
            
        if ($real->getEtat()=="ARCHIVE") {
            $real->setEtat('VALIDE');
        } else {
            $real->setEtat('ARCHIVE');
        }
        $this->em->persist($real);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_page_about');
        }
    }

    #[Route('/admin/page/about/section3/update', name: 'page_about_section3'), IsGranted("ROLE_ADMIN")]
    public function section3(Request $request){
        $files = $request->files;
        $page = $this->page;
        $page ->setMotTitre($request->request->get('motTitre'));
        $page -> setMotContenu($request->request->get('motContenue'));
        if(!empty($files->get("img2"))){
            $img=$files->get("img2"); 
            $imageName=uniqid().'.'.$img->guessExtension(); 
            $img->move($this->getParameter("pages_directory"),$imageName);
            $page->setMotPath($imageName);
        } 
        $this->em->persist($page);

        $this->em->flush();
        return $this->redirectToRoute('app_admin_page_about');

    }
}
