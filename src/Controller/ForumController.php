<?php

namespace App\Controller;

use DateTime;
use App\Entity\Sujet;
use App\Entity\Commentaire;
use App\Repository\UserRepository;
use App\Repository\SujetRepository;
use App\Repository\ThematiqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ForumController extends AbstractController
{
    #[Route('/forum/{filter}', name: 'app_forum',defaults:["filter"=>"all"]), IsGranted("ROLE_MEMBRE")]
    public function index($filter,
    SujetRepository $sujetRepo,
    ThematiqueRepository $thRepo,
    Request $request,
    PaginatorInterface $paginator
    ): Response
    {
        if(!empty($_GET)& isset($_GET['search'])){
            
            $options = $sujetRepo->findBy(['lisibilite'=>true],['publishedAt'=>'DESC']); 
            $key = $_GET['search'];
            $found = [];
            foreach($options as $sujet){
                if (strpos($sujet->getContenu(), $key) or strpos($sujet->getThematique()->getLibelle(), $key)) {
                    $found[] = $sujet;
                }
            }
            $thematiques = $thRepo->findAll();
            $output = $paginator->paginate(
                $found,
                $request->query->getInt('page',1),
                9
            );
            return $this->render('forum/index.html.twig', [
                'controller_name' => 'ForumController',
                'thematiques' => $thematiques,
                'sujets' => $output,
                'recherche' => 'recherche'
            ]);            
        }
        if($filter=="all"){
            $sujets = $sujetRepo->findBy(['lisibilite'=>true],['publishedAt'=>'DESC']);  
        }else{
            $thematique = $thRepo->findOneBy(['libelle'=>$filter]);
            //dd($thematique);
            $sujets = $sujetRepo->findBy(['lisibilite'=>true,'thematique'=>$thematique],['publishedAt'=>'DESC']);   
        }   
        $thematiques = $thRepo->findBy(['status'=>true]);
        $output = $paginator->paginate(
            $sujets,
            $request->query->getInt('page',1),
            9
        );
        return $this->render('forum/index.html.twig', [
            'thematiques' => $thematiques,
            'sujets' => $output
        ]);
    }

    #[Route('/admin/forum', name: 'app_admin_forum'), IsGranted("ROLE_ADMIN")] 
    public function adminIndex(SujetRepository $subRepo):Response{
        $sujets = $subRepo->findAll();
        $sujetsProposes = $subRepo->findBy(['etat'=>false],['createdAt'=>'DESC']); 
        $sujetsPublies = $subRepo->findBy(['lisibilite'=>true],['publishedAt'=>'DESC']);
        $sujetsArchives = $subRepo->findBy(['lisibilite'=>false],['publishedAt'=>'DESC']);
        return $this->render('forum/admin.forum.html.twig', [
            'controller_name' => 'ForumController',
            'sujets' => $sujets
        ]);
    }

    #[Route('/membre/forum/add', name: 'app_forum_add'), IsGranted("ROLE_MEMBRE")]
    public function addSujet(Request $request,EntityManagerInterface $em,ThematiqueRepository $themRepo):Response{
        if(!empty($_POST))
        {
            $sujet = new Sujet();
            $sujet->setAuteur($this->getUser());
            $sujet->setContenu($request->request->get("contenu"));
            $thematique = $themRepo->find(intval($request->request->get('thematique')));
            $sujet->setThematique($thematique);
            $slugger = new AsciiSlugger(); 
            $slug = $slugger->slug(uniqid("Sub"));
            $sujet->setSlug($slug);
            $em->persist($sujet);
            $em->flush();
        }
        return $this->redirectToRoute('app_forum');
    }

    #[Route('/forum/auteur/sujets', name: 'app_forum_auteur'), IsGranted("ROLE_MEMBRE")]
    public function mySubjects(SujetRepository $subRepo):Response{
        $sujets = $subRepo->findBy(['auteur'=>$this->getUser()],['createdAt'=>'DESC']);
        return $this->render('forum/membre.sujets.html.twig', [
            'controller_name' => 'ForumController',
            'sujets' => $sujets
        ]);
    }

    #[Route('/forum/sujet/{slug}', name: 'app_forum_sujet_details')]  
    public function SujetDetails($slug,SujetRepository $subRepo,CommentaireRepository $comRepo):Response{
        $sujet = $subRepo->findBy(['slug'=>$slug]);
        $comments = $comRepo->findBy(['sujet'=>$sujet[0]]);
        return $this->render('forum/sujet.details.html.twig', [
            'controller_name' => 'ForumController',
            'sujet' => $sujet[0],
            'comments' => $comments
        ]); 
    }

    #[Route('/admin/forum/sujet/{slug}', name: 'app_forum_admin_sujet_details'), IsGranted("ROLE_ADMIN")]  
    public function SujetDetailsAdmin(
        $slug,
        SujetRepository $subRepo,
        CommentaireRepository $comRepo,
        ThematiqueRepository $thRepo):Response{
        $sujet = $subRepo->findBy(['slug'=>$slug]);
        $comments = $comRepo->findBy(['sujet'=>$sujet[0]]);
        $thematiques = $thRepo->findAll();
        return $this->render('forum/admin.sujet.details.html.twig', [
            'controller_name' => 'ForumController',
            'sujet' => $sujet[0],
            'categories' => $thematiques,
            'comments' => $comments
        ]); 
    }

    #[Route('/comment/forum', name: 'app_sujet_comment')] 
    public function addComment(SujetRepository $subRepo, EntityManagerInterface $em,
    Request $request,UserRepository $userRepo):Response{
        $data = $request->request;
        $cmt = new Commentaire();
        if ($this->getUser()) {
        //$user = $userRepo -> find($this->getUser()->getId());
        $cmt -> setDate(new DateTime());
        $cmt -> setContent($data->get('content'));
        $cmt -> setSujet($subRepo -> findBy(['slug'=>$data->get('slug')],[],1)[0]);
        $cmt -> setUser($this->getUser());
        $em -> persist($cmt);
        $em -> flush();
        return $this->redirectToRoute('app_forum_sujet_details', ['slug' => $data->get('slug')]);
        }else{
            return $this-> redirectToRoute('app_login');
        }
    }

    #[Route('change/comment/{id}', name: 'app_sujet_alter_comment'), IsGranted("ROLE_ADMIN")] 
    public function changeCommentState(
        $id,
        CommentaireRepository $comRepo,
        EntityManagerInterface $em,
        SujetRepository $sujetRepo):Response{
            $comment = $comRepo->find($id);
            $comment->setIsVisible(!$comment->getIsVisible());
            $sujet = $sujetRepo->find($comment->getSujet()->getId());
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('app_forum_admin_sujet_details', ['slug' => $sujet->getSlug()]);
    }

    #[Route('alter/categorie/sujet', name: 'app_sujet_change_categorie'), IsGranted("ROLE_ADMIN")]
    public function changeThematiqueSujet(
    SujetRepository $suRepo,
    ThematiqueRepository $thRepo,
    Request $request,
    EntityManagerInterface $em):Response{
        if(!empty($_POST)){
            $sujet = $suRepo->findOneBy(["slug"=>$request->request->get('slug')]);
            $categorieSujet = $thRepo->find($request->request->get("cat"));
            $sujet->setThematique($categorieSujet);
            $em->persist($sujet);
            $em->flush();
            return $this->redirectToRoute('app_forum_admin_sujet_details', ['slug' => $sujet->getSlug()]);
        }
    }

}
