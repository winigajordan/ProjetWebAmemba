<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\CategorieProduit;
use App\Entity\Image;
use App\Entity\Produit;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProduitFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {

        // $product = new Product();
        // $manager->persist($product);
        $produit= new Produit();
        $produit->setLibelle("Produit Test");
        $produit->setPrix(1000);
        $produit->setDescription("Ceci est le test de cette description");
        $produit->setQteStock(5);
        $produit->setEtat(true);
        $produit->setSlug("slugTest");
        $image=new Image();
        $image->setPath("test.png");
        $produit->addImage($image);
        $categorie= new CategorieProduit();
        $categorie->setLibelle("Categorie Test0");
        $produit->setCategorie($categorie);
        $manager->persist($image);
        $manager->persist($categorie);
        $manager->persist($produit);
        $admin = (new Admin())
            ->setEmail("dev@admin.com")
            ->setPrenom("Dev")
            ->setNom("Admin")
            ->setRoles(["ROLE_ADMIN"]);
        $admin->setPassword($this->hasher->hashPassword($admin,'1234'));
        $manager->persist($admin);
        $manager->flush();
    }
}
