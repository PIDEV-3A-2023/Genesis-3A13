<?php

namespace App\Controller;

use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/question')]
class QuestionBackController extends AbstractController
{
    #[Route('/quest', name: 'app_question_back')]
    public function index(): Response
    {
        return $this->render('question_back/index.html.twig', [
            'controller_name' => 'QuestionBackController',
        ]);
    }
    #[Route('/search', name: 'app_question_search', methods: ['GET'])]
    public function search(Request $request,QuestionRepository $repo): Response
    {
        $nom = $request->get('nom');
        $resultat = $repo->searchByCompetitionName($nom);

        return $this->render('question/index.html.twig', [
            'questions' => $resultat,
        ]);
    }
}
