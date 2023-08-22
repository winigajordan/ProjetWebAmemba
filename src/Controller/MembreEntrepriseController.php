<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Repository\MembreRepository;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MembreEntrepriseController extends AbstractController
{
    public function __construct(
        EntrepriseRepository $entrepriseRepository,
        MembreRepository $membreRepository,
        EntityManagerInterface $em
        
    )
    {
        $this->entrepriseRepository = $entrepriseRepository;
        $this->membreRepository = $membreRepository;
        $this->em = $em;
        
    }

    #[Route('/membre/entreprise', name: 'app_membre_entreprise'), IsGranted("ROLE_MEMBRE")]
    public function index(): Response
    {
        if($this->getUser()){
            $entreprise = $this->entrepriseRepository->findOneBy(['proprietaire'=>$this->membreRepository->find($this->getUser()->getId())]);
            if ($entreprise) {
                return $this->render('membre_entreprise/index.html.twig', [
                'entreprise'=>$entreprise,
            ]); 
            } else {
                return $this->render('membre_entreprise/index.html.twig', [
            ]); 
            }
            
        } else {
            return $this->redirectToRoute('app_login');
        }
        
    }


    #[Route('/membre/entreprise/add', name: 'app_membre_entreprise_add', methods:('POST')), IsGranted("ROLE_MEMBRE")]
    public function add(Request $request): Response
    {
        $files = $request->files;
        $data =  $request->request;
        $entreprise = new Entreprise();
        $entreprise -> setNom($data->get('nom'));
        $entreprise -> setDomaine($data->get('domaine'));
        $entreprise -> setDescription($data->get('description'));
        $entreprise -> setAdresses($data->get('adresse'));
        $entreprise -> setSlug($this->slg($data->get('nom')));
        $entreprise -> setProprietaire($this->membreRepository->find($this->getUser()->getId()));
        $entreprise -> setEmail($data->get('email'));
        $entreprise -> setInstagram($data->get('instagram'));
        $entreprise -> setType($data->get('type'));
        $entreprise -> setCreatedAt(new \DateTime($data->get('date')));
        if (!empty($data->get('site'))){
            $entreprise -> setSite($data->get('site'));
        }
        $entreprise -> setEtat('DEMANDE');
        if(!empty($files->get("logo"))){
            $img=$files->get("logo"); 
            $imageName=uniqid().'.'.$img->guessExtension(); 
            $img->move($this->getParameter("entreprises_directory"),$imageName);
            $entreprise->setLogo($imageName);
        }
        //dd($entreprise);
        $this->em->persist($entreprise);
        $this->em->flush();
        return $this->redirectToRoute('app_membre_entreprise');
    }

    #[Route('/membre/entreprise/update', name: 'app_membre_entreprise_update', methods:('POST')), IsGranted("ROLE_MEMBRE")]
    public function update(Request $request): Response
    {
        $files = $request->files;
        $data =  $request->request;
        $entreprise = $this->entrepriseRepository->find($data->get('id'));
        $entreprise -> setNom($data->get('nom'));
        $entreprise -> setDomaine($data->get('domaine'));
        $entreprise -> setDescription($data->get('description'));
        $entreprise -> setAdresses($data->get('adresse'));
        $entreprise -> setSlug($this->slg($data->get('nom')));
        $entreprise -> setProprietaire($this->membreRepository->find($this->getUser()->getId()));
        $entreprise -> setEtat('DEMANDE');
        if(!empty($files->get("logo"))){
            $img=$files->get("logo"); 
            $imageName=uniqid().'.'.$img->guessExtension(); 
            $img->move($this->getParameter("entreprises_directory"),$imageName);
            $entreprise->setLogo($imageName);
        }
        if ($data->get('etat')) {
            $entreprise -> setEtat($data->get('etat'));
        }
        $this->em->persist($entreprise);
        $this->em->flush();
        return $this->redirectToRoute('app_membre_entreprise');
    }

    public function slg($slug){
        return str_replace(" ","-",strtolower($slug));
    }
}
