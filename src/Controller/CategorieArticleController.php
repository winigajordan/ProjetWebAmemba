<?php

namespace App\Controller;

use App\Entity\CategorieArticle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategorieArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieArticleController extends AbstractController
{
    #[Route('admin/categorie/article', name: 'app_categorie_article')]
    public function index(CategorieArticleRepository $catRepo): Response
    {
        $categories = $catRepo->findAll();
        return $this->render('categorie_article/index.html.twig', [
            'controller_name' => 'CategorieArticleController',
            'categories' => $categories
        ]);
    }

    #[Route('/admin/article/categorie/add', name: 'app_categorie_article_add')]
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
}
