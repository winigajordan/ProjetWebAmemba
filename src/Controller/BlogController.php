<?php

namespace App\Controller;

use DateTime;
use App\Entity\Image;
use DateTimeImmutable;
use App\Entity\Article;
use App\Entity\Commentaire;
use App\Entity\CategorieArticle;
use App\Repository\UserRepository;
use App\Repository\ImageRepository;
use App\Repository\ArticleRepository;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategorieArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{

    private $articleRepo;
    private $em;
    private Request $request;

    public function __construct(ArticleRepository $articleRepo,EntityManagerInterface $em){
        $this->articleRepo=$articleRepo;
        $this->em=$em;
    }

    #[Route('/actualite', name:'blog')]
    public function blog( CategorieArticleRepository $catRepo, ArticleRepository $articleRepo, PaginatorInterface $paginator, Request $request,){
        $articles = $articleRepo->findBy(['lisibilite'=>true],['publishedAt'=>'DESC']); 
        $output = $paginator->paginate(
            $articles,
            $request->query->getInt('page',1),
            12
        );
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $output,
            'recherche' => 'recherche',
            'categories' => $catRepo->findAll()
        ]);   
    }

    #[Route('/blog/categorie/{filter}', name: 'app_blog',defaults:['filter'=>'all'])]
    public function index($filter, 
    ArticleRepository $articleRepo,
    CategorieArticleRepository $catRepo,
    Request $request,
    PaginatorInterface $paginator): Response
    {
        if(!empty($_GET)){
            if(isset($_GET['search'])){
                $options = $articleRepo->findBy(['lisibilite'=>true],['publishedAt'=>'DESC']); 
                $key = $_GET['search'];
                $found = [];
                foreach($options as $article){
                    if (strpos($article->getTitre(), $key)||strpos($article->getCategorieArticle()->getLibelle(), $key)) {
                        $found[] = $article;
                        //dd($found);
                    }
                }
                $categories = $catRepo->findAll();
                $output = $paginator->paginate(
                    $found,
                    $request->query->getInt('page',1),
                    12
                );
                return $this->render('blog/index.html.twig', [
                    'controller_name' => 'BlogController',
                    'articles' => $output,
                    'recherche' => 'recherche',
                    'categories' => $categories
                ]);   
            }
                      
        }
        if($filter!="all"){
            $categorie = $catRepo->findOneBy(['libelle'=>$filter]);
            $articles = $articleRepo->findBy(['lisibilite'=>true,'categorieArticle'=>$categorie],['publishedAt'=>'DESC']);
        }else{
           $articles = $articleRepo->findBy(['lisibilite'=>true],['publishedAt'=>'DESC']); 
        }
        $categories = $catRepo->findAll();
        $output = $paginator->paginate(
            $articles,
            $request->query->getInt('page',1),
            12
        );
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $output,
            'categories' => $categories
        ]);
    }

    #[Route('/membre/blog/article/add', name: 'app_blog_article_add')]
    public function addArticle(Request $request,EntityManagerInterface $em,
    CategorieArticleRepository $catRepo): Response{

        if(!empty($_POST)){
            //dd($request);
            $article = new Article();
            $article->setAuteur($this->getUser());
            $article->setContenu($request->request->get("contenu"));
            //dd($request->request->get("contenu"));
            $article->setTitre($request->request->get("titre"));
            $slugger = new AsciiSlugger();
            $slug = $slugger->slug($request->request->get("titre"));
            $article->setSlug($slug);
            $categorie = $catRepo->find($request->request->get("categorie"));
            $article->setCategorieArticle($categorie);
            $image=new Image(); 
            $img=$request->files->get("image"); 
            $imageName=uniqid().'.'.$img->guessExtension(); 
            $img->move($this->getParameter("blog"),$imageName);          
            $image->setPath($imageName);
            $article->addImage($image);
            $em->persist($image);
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('app_article_auteur');
        }
        $categories = $catRepo->findBy(['status'=>true]);
        return $this->render('blog/membre.article.add.html.twig', [
            'controller_name' => 'BlogController',
            'categories' => $categories
        ]);
    }

    #[Route('/blog/auteur/articles', name: 'app_article_auteur')]
    public function myArticles(ArticleRepository $articleRepo):Response{
        $articles = $articleRepo->findBy(['auteur'=>$this->getUser()],['createdAt'=>'DESC']);
        //dd($articles);
        return $this->render('blog/membre.articles.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles
        ]);
    }

    #[Route('/blog/article/{slug}', name: 'app_blog_article_details')]  
    public function articleDetails($slug,ArticleRepository $articleRepo,ImageRepository $imageRepo,Request $request,
    CommentaireRepository $comRepo):Response{
        $article = $articleRepo->findBy(['slug'=>$slug]);
        //dd($request);\
        $comments = $comRepo->findBy(['article'=>$article[0]]);
        //dd($comments);
        $paragraphes =explode("\n\r",$article[0]->getContenu());
        
        //dd($paragraphes);
        $image = $imageRepo->find($article[0]->getImages()[0]->getId());
        //dd($image);
        //dd($article[0]->getImages()[0]);
        return $this->render('blog/article.details.html.twig', [
            'controller_name' => 'BlogController',
            'article' => $article[0],
            'paragraphes' => $paragraphes,
            'comments' => $comments
        ]); 
    }
    
    #[Route('/admin/blog', name: 'app_admin_article')] 
    public function adminIndex(ArticleRepository $articleRepo):Response{
        $articles = $articleRepo->findAll();
        $articlesProposes = $articleRepo->findBy(['refused'=>false],['createdAt'=>'DESC']); 
        $articlesPublies = $articleRepo->findBy(['lisibilite'=>true],['publishedAt'=>'DESC']);
        $articlesArchives = $articleRepo->findBy(['lisibilite'=>false,'statut'=>true],['publishedAt'=>'DESC']);
        return $this->render('blog/admin.article.list.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articlesProposes
        ]);
    }

    #[Route('/admin/blog/article/{slug}', name: 'app_admin_article_details')]
    public function AdminArticleDetails(
        $slug,
        ArticleRepository $articleRepo,
        ImageRepository $imageRepo,
        EntityManagerInterface $em,
        Request $request,
        CategorieArticleRepository $catRepo):Response
        {
        $article = $articleRepo->findBy(['slug'=>$slug]);
        $paragraphes =explode("\n\r",$article[0]->getContenu());
        
        $categoriesArticle = $catRepo->findAll();
        
        if(!empty($_POST)){
            if($request->request->get('valider')){
                $art=$article[0];
                $art->setLisibilite(true);
                $art->setStatut(true);
                $art->setPublishedAt(new DateTimeImmutable());
                $this->em->persist($art);
                $this->em->flush();
                
                return $this->redirectToRoute('app_admin_article');
            }
            if($request->request->get('refuser')){
                $art=$article[0];
                $art->setRefused(true);
                $this->em->persist($art);
                $this->em->flush();
                return $this->redirectToRoute('app_admin_article');
            }
            if($request->request->get('visibility')){
                $art=$article[0];
                $art->setLisibilite(!$art->getLisibilite());
                $this->em->persist($art);
                $this->em->flush();
                return $this->render('blog/admin.article.details.html.twig', [
                    'controller_name' => 'BlogController',
                    'categories' => $categoriesArticle,
                    'article' => $art,
                    'paragraphes' => $paragraphes
                ]); 
            }
        }
        return $this->render('blog/admin.article.details.html.twig', [
            'controller_name' => 'BlogController',
            'categories' => $categoriesArticle,
            'article' => $article[0],
            'paragraphes' => $paragraphes
        ]); 
    }

    #[Route('/comment/article', name: 'app_article_comment')] 
    public function addComment(ArticleRepository $articleRepo, EntityManagerInterface $em,
    Request $request,UserRepository $userRepo){
        $data = $request->request;
        //dd($data);
        $cmt = new Commentaire();
        if ($this->getUser()) {
            
            //$user = $userRepo -> find($this->getUser()->getId());
            $cmt -> setDate(new DateTime());
            $cmt -> setContent($data->get('content'));
            $cmt -> setArticle($articleRepo -> findBy(['slug'=>$data->get('slug')],[],1)[0]);
            $cmt -> setUser($this->getUser());
            //dd($cmt);
            $em -> persist($cmt);
            $em -> flush();
            //return $this-> redirectToRoute('app_login');
            return $this->redirectToRoute('app_blog_article_details', ['slug' => $data->get('slug')]);
        }
        return $this-> redirectToRoute('app_login');
        
    }

    #[Route('alter/comment/{id}', name: 'app_article_alter_comment')] 
    public function changeCommentState(
        $id,
        CommentaireRepository $comRepo,
        EntityManagerInterface $em,
        ArticleRepository $articleRepo):Response{
            $comment = $comRepo->find($id);
            $comment->setIsVisible(!$comment->getIsVisible());
            $article = $articleRepo->find($comment->getArticle()->getId());
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('app_admin_article_details', ['slug' => $article->getSlug()]);
    }
 
    #[Route('alter/categorie/article', name: 'app_article_change_categorie')]
    public function changeCategorieArticle(
    ArticleRepository $articleRepo,
    CategorieArticleRepository $catRepo,
    Request $request,
    EntityManagerInterface $em):Response{
        if(!empty($_POST)){
            $article = $articleRepo->findOneBy(["slug"=>$request->request->get('slug')]);
            $categorieArticle = $catRepo->find($request->request->get("cat"));
            $article->setCategorieArticle($categorieArticle);
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('app_admin_article_details', ['slug' => $article->getSlug()]);
        }
    }
}