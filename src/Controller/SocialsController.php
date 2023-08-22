<?php

namespace App\Controller;

use App\Repository\FooterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class SocialsController extends AbstractController
{
    public function indexFooter(FooterRepository $footRepo)
    {
        $footer = $footRepo->find(1);
        return $this->render('socials.html.twig',[
            'controller_name' => 'FooterController',
            'foot' => $footer
        ]);
    }
}