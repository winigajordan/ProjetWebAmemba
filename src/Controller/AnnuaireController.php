<?php

namespace App\Controller;

use App\Repository\MembreRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AnnuaireController extends AbstractController
{
    public function __construct(
        MembreRepository $membreRepository
    )
    {
        $this->membreRepository = $membreRepository;
    }

    #[Route('/annuaire', name: 'app_annuaire'), IsGranted("ROLE_MEMBRE")]
    public function index(): Response
    {
        return $this->render('annuaire/index.html.twig', [
            'membres' => $this->membreRepository->findAll(),
            
        ]);
    }

    #[Route('/annuaire/{prenom}-{nom}', name: 'app_annuaire_details', methods: ['GET']), IsGranted("ROLE_MEMBRE")]
    public function details($prenom, $nom): Response
    {
        $membre = $this->membreRepository->findOneBy([
            'prenom' => $prenom,
            'nom' => $nom,
        ]);
        if ($membre == null) {
            return $this->redirectToRoute('app_annuaire');
        }
        else {
            return $this->render('annuaire/details.html.twig', [
                'membre' => $membre,
            ]);
        }
    }

    #[Route('/annuaire/recherche', name: 'app_annuaire_details_recherche', methods: ['POST']), IsGranted("ROLE_MEMBRE")]
    public function recherche(Request $request): Response
    {
        
        return $this->render('annuaire/index.html.twig', [
            'membres' => $this->find($request->request->get('search'))
        ]);
    }

    public function find($key){
        $membres = $this->membreRepository->findAll();
        $result = [];
        $key = strtolower($key);
        foreach ($membres as $membre) {
            if (strpos(strtolower($membre->getPrenom()), $key) !== false || strpos(strtolower($membre->getNom()), $key) !== false) {
                $result[] = $membre;
            }
        }
        return $result;
    }

}
