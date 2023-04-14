<?php

namespace App\Controller;

use App\Entity\Competition;
use App\Repository\CompetitionRepository;
use App\Repository\QuestionRepository;
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
    #[Route('/{idCompetition}', name: 'app_competition_show_front', methods: ['GET'])]
    public function show(Competition $competition): Response
    {
        return $this->render('competition_front/show.html.twig', [
            'competition' => $competition,
        ]);
    }
    #[Route('/quiz/{idCompetition}', name: 'app_competition_quiz_front', methods: ['GET'])]
    public function quiz(QuestionRepository $repo,$idCompetition): Response
    {
        $questions=$repo->findQuestionsByCompetition($idCompetition);
        return $this->render('competition_front/quiz.html.twig', [
            'questions' => $questions,
        ]);
    }
    
}
