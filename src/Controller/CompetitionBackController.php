<?php

namespace App\Controller;

use App\Entity\Competition;
use App\Repository\CompetitionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/competition')]
class CompetitionBackController extends AbstractController
{
    #[Route('/com', name: 'app_competition_back')]
    public function index(): Response
    {
        return $this->render('competition_back/index.html.twig', [
            'controller_name' => 'CompetitionBackController',
        ]);
    }
    #[Route('/search', name: 'app_competition_search', methods: ['GET'])]
    public function search(Request $request,CompetitionRepository $repo): Response
    {
        $nom = $request->get('nom');
        $resultat = $repo->SearchByNom($nom);

        return $this->render('competition/index.html.twig', [
            'competitions' => $resultat,
        ]);
    }

}
