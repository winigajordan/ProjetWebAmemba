<?php

namespace App\DataFixtures;

use App\Entity\CategorieEvenement;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategorieEvenementFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = ['Réunion','Rencontre','Assemblée génégrale','Evenement'];
        foreach($categories as $val){
            $cat = new CategorieEvenement();
            $cat -> setName($val);
            $manager -> persist($cat);
        };
        $manager->flush();
    }
}
