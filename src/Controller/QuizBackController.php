<?php

namespace App\Controller;

use App\Repository\QuizRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/quiz')]
class QuizBackController extends AbstractController
{
    #[Route('/qu', name: 'app_quiz_back')]
    public function index(): Response
    { $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('quiz_back/index.html.twig', [
            'controller_name' => 'QuzBackController',
        ]);
    }
    #[Route('/search', name: 'app_quiz_search', methods: ['GET'])]
    public function search(Request $request,QuizRepository $repo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $nom = $request->get('nom');
        $resultat = $repo->searchByCompetitionName($nom);

        return $this->render('quiz/index.html.twig', [
            'quizzes' => $resultat,
        ]);
    }
}
