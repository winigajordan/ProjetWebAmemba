<?php

namespace App\Controller;

use App\Entity\CategorieArticle;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategorieArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\Length;

class CategorieArticleController extends AbstractController
{
    #[Route('admin/categorie/article', name: 'app_categorie_article'), IsGranted("ROLE_ADMIN")]
    public function index(CategorieArticleRepository $catRepo): Response
    {
        $categories = $catRepo->findAll();
        return $this->render('categorie_article/index.html.twig', [
            'controller_name' => 'CategorieArticleController',
            'categories' => $categories
        ]);
    }

    #[Route('/admin/article/categorie/add', name: 'app_categorie_article_add'), IsGranted("ROLE_ADMIN")]
    public function addCategorie(Request $request,EntityManagerInterface $em): Response
    {
        if(!empty($_POST)){
            $cat = new CategorieArticle();
            $cat->setLibelle($request->request->get('libelle'));
            $em->persist($cat);
            $em->flush();
        }
        return $this->redirectToRoute('app_categorie_article');
    }

    #[Route('admin/categorie/article/{id}', name: 'app_categorie_article_update'), IsGranted("ROLE_ADMIN")]
    public function updateCategorie(CategorieArticleRepository $catRepo,$id,EntityManagerInterface $em,Request $request): Response
    {
        $cat = $catRepo->find($id);
        if(!empty($_POST)){
            $cat->setLibelle($request->request->get('libelle'));
            $em->persist($cat);
            $em->flush();
            return $this->redirectToRoute('app_categorie_article');
        }else{
            
            $categories = $catRepo->findAll();
            return $this->render('categorie_article/index.html.twig', [
                'controller_name' => 'CategorieArticleController',
                'categories' => $categories,
                'categorie' => $cat,
            ]); 
        }
        
    }

    #[Route('admin/categorie/article/archive/{id}', name: 'app_categorie_article_archive'), IsGranted("ROLE_ADMIN")]
    public function removeCategorie(CategorieArticleRepository $catRepo,ArticleRepository $artRepo,EntityManagerInterface $em,$id):  Response
    {
        $cat = $catRepo->find($id);
        /* $articles = $artRepo->findBy(['categorieArticle'=>$cat]);
        if(sizeof($articles)>0){
            foreach ($articles as $article) {
                $article->setLisibilite(false);
                $em->persist($article);
            }
        }*/
        $cat->setStatus(!$cat->getStatus()); 
        $em->persist($cat);
        $em->flush();
        return $this->redirectToRoute('app_categorie_article');
    }



}
