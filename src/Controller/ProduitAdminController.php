<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategorieProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitAdminController extends AbstractController
{

    #[Route('/admin/produit', name: 'app_produit_admin'), IsGranted("ROLE_ADMIN")]
    public function index(ProduitRepository $prodRepo): Response
    {
        $produits=$prodRepo->findAll();
        return $this->render('produit_admin/index.html.twig', [
            'controller_name' => 'ProduitAdminController',
            'produits' => $produits
        ]);
    }

    #[Route('/admin/produit/add', name: 'app_produit_add'), IsGranted("ROLE_ADMIN")]
    public function addProduit(ProduitRepository $prodRepo,CategorieProduitRepository $catRepo,
     Request $request,EntityManagerInterface $em,ValidatorInterface $validator): Response
    {
        $categories=$catRepo->findAll();
        if (!empty($_POST)){
            //dd($request);
            $produit=new Produit();
            $produit->setLibelle($request->request->get("libelle"));
            $produit->setDescription($request->request->get("description"));
            $produit->setPrix($request->request->get("prix"));
            $produit->setQteStock($request->request->get("qte_stock"));

            $image=new Image(); 
            //dd($request);
            $img=$request->files->get("image"); 
            $imageName=uniqid().'.'.$img->guessExtension(); 
            $img->move($this->getParameter("images_directory"),$imageName);          
            $image->setPath($imageName);
            $produit->addImage($image);


            $categorie=$catRepo->find($request->request->get("categorie"));
            $produit->setCategorie($categorie);
            $slugger = new AsciiSlugger();
            $slug = $slugger->slug($request->request->get("libelle"));
            //dd($slug);
            $produit->setSlug($slug);
            $produit->setEtat(true);
            $errors=$validator->validate($produit);
            if(count($errors)>0){
                dd($errors);
            }else{
                $em->persist($image);
                $em->persist($produit);
                $em->flush();
            }
            return $this->redirectToRoute("app_produit_admin");            
        }
        
        return $this->render('produit_admin/admin.add.produit.html.twig', [
            'controller_name' => 'ProduitAdminController',
            'categories' => $categories
        ]);
    }
    
    #[Route('/admin/produit/edit/{slug}', name:'app_produit_edit'), IsGranted("ROLE_ADMIN")]
    public function editProduit($slug,ProduitRepository $prodRepo,
    CategorieProduitRepository $catRepo,Request $request,ValidatorInterface $validator,
    EntityManagerInterface $em):Response{
        $categories=$catRepo->findAll();
        $produit=$prodRepo->findOneBy(['slug' => $slug]);
        if($produit==null){
            return $this->redirectToRoute("app_produit_admin");
        }
        if (!empty($_POST)){
            //dd($request);
            $produit->setLibelle($request->request->get("libelle"));
            $produit->setDescription($request->request->get("description"));
            $produit->setPrix($request->request->get("prix"));
            $produit->setQteStock($request->request->get("qte_stock"));
            if($request->files->get("image")){
                $image=new Image(); 
                $img=$request->files->get("image"); 
                $imageName=uniqid().'.'.$img->guessExtension(); 
                $img->move($this->getParameter("images_directory"),$imageName);          
                $image->setPath($imageName);
                unlink($this->getParameter("images_directory")."/".$produit->getImages()[0]->getPath());
                $em->remove($produit->getImages()[0]);
                $produit->removeImage($produit->getImages()[0]);
                $produit->addImage($image);
                $em->persist($image);
            }
            /* $categorie=$catRepo->find($request->request->get("categorie"));
            $produit->setCategorie($categorie); */
            $errors=$validator->validate($produit);
            if(count($errors)>0){
                dd($errors);
            }else{        
                $em->persist($produit);
                $em->flush();
            }
            return $this->redirectToRoute("app_produit_admin");            
        }
        return $this->render('produit_admin/admin.add.produit.html.twig', [
            'controller_name' => 'ProduitAdminController',
            'produitSelected' => $produit,
            'categories' => $categories
        ]);
    }

    #[Route('/admin/produit/archive/{slug}', name:'app_produit_archive'), IsGranted("ROLE_ADMIN")]
    public function archiveProduit($slug,ProduitRepository $prodRepo,
    EntityManagerInterface $em):Response{
        $produit=$prodRepo->findOneBy(['slug'=> $slug]);
        $produit->setEtat(!$produit->getEtat());
        $em->persist($produit);
        $em->flush();
        return $this->redirectToRoute("app_produit_admin"); 
    }
}
