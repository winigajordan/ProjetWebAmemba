<?php

namespace App\DataFixtures;

use App\Entity\Demande;
use DateTime;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class DemandeFixtures extends AppFixtures
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        //$mails = ['winigajordan@gmail.com', 'akolatse1@gmail.com'];

        /*
        $demande = new Demande();
        $demande -> setNom("Rema");
        $demande -> setPrenom("Jordan");
        $demande -> setMail("winigajordan@gmail.com");
        $demande -> setPays("Senegal");
        $demande -> setPromotion("2019");
        $demande -> setVille("Dakar");
        $demande -> setDate(new DateTime());
        $demande -> setEtat('EN COURS');
        $manager-> persist($demande);


        $demande1 = new Demande();
        $demande1 -> setNom("Akol");
        $demande1 -> setPrenom("Staph");
        $demande1 -> setMail("akolatse1@gmail.com");
        $demande1 -> setPays("Senegal");
        $demande1 -> setPromotion("2019");
        $demande1 -> setVille("Dakar");
        $demande1 -> setDate(new DateTime());
        $demande1 -> setEtat('EN COURS');
        $manager-> persist($demande1);

        $manager->flush();
        */
    }
}
