<?php

namespace App\Controller;

use App\Repository\CompetitionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/competitions')]
class CompetitionFrontController extends AbstractController
{
    #[Route('/', name: 'app_competition_front')]
    public function index(CompetitionRepository $repo): Response
    {
        $competitions = $repo->findAll();
      
        return $this->render('competition_front/index.html.twig', [
            'competitions' => $competitions,
            'controller_name' => 'CompetitionFrontController',
        ]);
    }
}
