<?php

namespace App\Controller;

use App\Repository\MembreRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnnuaireController extends AbstractController
{
    private $paginator;
    private $membreRepository;
    public function __construct(
        MembreRepository $membreRepository,
        PaginatorInterface $paginator
    )
    {
        $this->membreRepository = $membreRepository;
        $this->paginator = $paginator;
    }

    #[Route('/annuaire', name: 'app_annuaire'), IsGranted("ROLE_MEMBRE")]
    public function index(Request $request): Response
    {
        $membres = $this->membreRepository->findBy(['etat'=>true]);
        $output = $this->paginator->paginate(
            $membres,
            $request->query->getInt('page',1),
            16
        );
        return $this->render('annuaire/index.html.twig', [
            'membres' => $output,
        ]);
    }

    #[Route('/annuaire/{prenom}_{nom}', name: 'app_annuaire_details', methods: ['GET']), IsGranted("ROLE_MEMBRE")]
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
        $membres = $this->membreRepository->findBy(['etat'=>true]);
        $output = $this->paginator->paginate(
            $this->find($request->request->get('search')),
            $request->query->getInt('page',1),
            16
        );
        return $this->render('annuaire/index.html.twig', [
            'membres' => $output,
        ]);

    }

    public function find($key){
        $membres = $this->membreRepository->findBy(['etat'=>true]);
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
