<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Repository\AdminRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminAddAccountController extends AbstractController
{
    private UserPasswordHasherInterface $hasher;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    #[Route('/account', name: 'app_admin_account'), IsGranted("ROLE_ADMIN")]
    public function index(AdminRepository $adminRipo): Response
    {
        return $this->render('admin/admin_add_account/index.html.twig', [
            'admins'=>$adminRipo->findAll()
        ]);
    }

    #[Route('/account/add', name: 'app_admin_account_add'), IsGranted("ROLE_ADMIN")]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $data=$request->request;
        $admin = (new Admin())
        ->setNom($data->get('nom'))
        ->setPrenom($data->get('prenom'))
        ->setEmail($data->get('email'));
        $admin->setPassword($this->hasher->hashPassword($admin, $data->get('pwd')));
        $em->persist($admin);
        $em->flush();
       return  $this->redirectToRoute('app_admin_account');
    }

    #[Route('/account/delete/{id}', name: 'app_admin_account_delete'), IsGranted("ROLE_ADMIN")]
    public function update($id, EntityManagerInterface $em, AdminRepository $adminRipo): Response
    {
        $admin = $adminRipo->find($id);
        $em->remove($admin);
        $em->flush();
        return  $this->redirectToRoute('app_admin_account');
    }


}
