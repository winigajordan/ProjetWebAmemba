<?php

namespace App\Controller;

use App\Entity\PageAccueil;
use App\Repository\PageAccueilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminPageAccueilController extends AbstractController
{
   
    public function __construct(
        PageAccueilRepository $accueilRipo,
        EntityManagerInterface $em,
    ){
        $this->accueilRipo = $accueilRipo;
        $this->em = $em;
        $this->page =  $this->accueilRipo->find(1);
    }

    #[Route('/admin/page/accueil', name: 'app_admin_page_accueil'), IsGranted("ROLE_ADMIN")]
    public function index(): Response
    {
        return $this->render('admin/admin_page_accueil/index.html.twig', [
            'page' => $this->page,
        ]);
    }


    #[Route('/admin/page/accueil/section1/update', name: 'page_accueil_section1', methods:('POST')), IsGranted("ROLE_ADMIN")]
    public function section1(Request $request): Response
    {
        $files = $request->files;
        $data =  $request->request;
        $page = $this->page;
        $page -> setCarouselTitre1($data->get('titre1'));
        $page -> setCarouselTitre2($data->get('titre2'));
        $page -> setCarouselTitre3($data->get('titre3'));
        $page -> setCarouselText1($data->get('text1'));
        $page -> setCarouselText2($data->get('text2'));
        $page -> setCarouselText3($data->get('text3'));

        if(!empty($files->get("img1"))){
            $img=$files->get("img1"); 
            $imageName=uniqid().'.'.$img->guessExtension(); 
            $img->move($this->getParameter("pages_directory"),$imageName);
            $page->setCarouselImage1($imageName);
        } 
        
        if(!empty($files->get("img2"))){
            $img=$files->get("img2"); 
            $imageName=uniqid().'.'.$img->guessExtension(); 
            $img->move($this->getParameter("pages_directory"),$imageName);
            $page->setCarouselImage2($imageName);
        } 
        
        if(!empty($files->get("img3"))){
            $img=$files->get("img3"); 
            $imageName=uniqid().'.'.$img->guessExtension(); 
            $img->move($this->getParameter("pages_directory"),$imageName);
            $page->setCarouselImage3($imageName);
        } 
        
        $this->em->persist($page);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_page_accueil');
    }

    
    #[Route('/admin/page/accueil/section2/update', name: 'page_accueil_section2',  methods:('POST')), IsGranted("ROLE_ADMIN")]
    public function section2(Request $request): Response
    {
        $files = $request->files;
        $data = $request->request;
        $page= $this->page;
        $page->setMissionTitre($data->get('missionTitre'));
        //dd($data->get('my_editor'));
        $page->setMissionText($data->get('my_editor'));
        if(!empty($files->get("missionImg"))){
            $img=$files->get("missionImg"); 
            $imageName=uniqid().'.'.$img->guessExtension(); 
            $img->move($this->getParameter("pages_directory"),$imageName);
            $page->setMissionImg($imageName);
        } 
        $this->em->persist($page);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_page_accueil');
    }

    #[Route('/admin/page/accueil/section3/update', name: 'page_accueil_section3',  methods:('POST')), IsGranted("ROLE_ADMIN")]
    public function section3(Request $request): Response
    {
        $data = $request->request;
        //dd($data);
        $page= $this->page;
        $page->setChiffreAlumni($data->get('chiffreAlumni'));
        $page->setChiffreAlumniText($data->get('chiffreAlumniText'));
        $page->setChiffreProjet($data->get('chiffreProjet'));
        $page->setChiffreProjetText($data->get('chiffreProjetText'));
        $page->setChiffreFonds($data->get('chiffreFonds'));
        $page->setChiffreFondsText($data->get('chiffreFondsText'));
        $this->em->flush();
        return $this->redirectToRoute('app_admin_page_accueil');
    }

    #[Route('/admin/page/accueil/section4/update', name: 'page_accueil_section4',  methods:('POST')), IsGranted("ROLE_ADMIN")]
    public function section4(Request $request): Response
    {
        $data = $request->request;
        $page= $this->page;
        $page->setEntrepriseTitre($data->get('entrepriseTitre'));
        $page->setEntrepriseTexte($data->get('entrepriseTexte'));
        $this->em->persist($page);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_page_accueil');
    }

    #[Route('/admin/page/accueil/section5/update', name: 'page_accueil_section5',  methods:('POST')), IsGranted("ROLE_ADMIN")]
    public function section5(Request $request): Response
    {
        $files = $request->files;
        $data = $request->request;
       //dd($data);
        $page= $this->page;
        $page->setTemoignageAuteur1($request->request->get('temoignageAuteur1')); 
        $page->setTemoignageAuteur2($request->request->get('temoignageAuteur2'));
        $page->setTemoignageAuteur3($request->request->get('temoignageAuteur3'));
        $page->setTemoignageAuteur4($request->request->get('temoignageAuteur4'));
        $page->setTemoignageTitre1($request->request->get('temoignageTitre1'));
        $page->setTemoignageTitre2($request->request->get('temoignageTitre2'));
        $page->setTemoignageText1($request->request->get('temoignageText1'));
        $page->setTemoignageText2($request->request->get('temoignageText2'));
        $page->setTemoignageText3($request->request->get('temoignageText3'));
        $page->setTemoignageText4($request->request->get('temoignageText4'));
        if(!empty($files->get("ancienneImg1"))){
            $img=$files->get("ancienneImg1"); 
            $imageName=uniqid().'.'.$img->guessExtension(); 
            $img->move($this->getParameter("pages_directory"),$imageName);
            $page->setAncienneImg1($imageName);
        } 
        if(!empty($files->get("ancienneImg2"))){
            $img=$files->get("ancienneImg2"); 
            $imageName=uniqid().'.'.$img->guessExtension(); 
            $img->move($this->getParameter("pages_directory"),$imageName);
            $page->setAncienneImg2($imageName);
        } 
        $this->em->persist($page);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_page_accueil');
    }

    #[Route('/admin/page/accueil/section6/update', name: 'page_accueil_section6',  methods:('POST')), IsGranted("ROLE_ADMIN")]
    public function section6(Request $request): Response
    {
        $data = $request->request;
        //dd($data);
        $page= $this->page;
        $page->setBlogTitre($data->get('blogTitre'));
        $page->setBlogText($data->get('blogText'));
        $this->em->persist($page);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_page_accueil');
    }
    
    #[Route('/admin/page/accueil/section7/update', name: 'page_accueil_section7',  methods:('POST')), IsGranted("ROLE_ADMIN")]
    public function section7(Request $request): Response
    {
        $files = $request->files;
        $data = $request->request;
        //dd($data);
        $page= $this->page;
        $page->setMembreTitre($data->get('membreTitre'));
        $page->setMembreText($data->get('membreText'));
        if(!empty($files->get("ancienneImg3"))){
            $img=$files->get("ancienneImg3"); 
            $imageName=uniqid().'.'.$img->guessExtension(); 
            $img->move($this->getParameter("pages_directory"),$imageName);
            $page->setAncienneImg3($imageName);
        } 
        $this->em->persist($page);
        $this->em->flush();
        return $this->redirectToRoute('app_admin_page_accueil');
    }

}
