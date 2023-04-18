<?php

namespace App\Controller;

use App\Entity\Competition;
use App\Entity\ResulatQuiz;
use App\Repository\CompetitionRepository;
use App\Repository\QuestionRepository;
use App\Repository\QuizRepository;
use App\Repository\ResultatQuizRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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
    public function quiz(QuestionRepository $repo, $idCompetition, CompetitionRepository $competitionRepo): Response
    {
        $score = 0;

        $comp = $idCompetition;
       
        $questions = $repo->findQuestionsByCompetition($idCompetition);
        return $this->render('competition_front/quiz.html.twig', [
            'questions' => $questions,
            'comp' => $comp,
           
            'totalQuestions' => count($questions),
            'score' => $score,
        ]);
    }
    #[Route('/quiz/participer/{comp}', name: 'app_competition_participer_front')]
    public function participate(Request $request, QuestionRepository $repo, $comp, UtilisateurRepository $repouser, ResultatQuizRepository $repores, QuizRepository $repoQuiz, competitionRepository $repoComp, Security $security): Response
    {
        $Client = $security->getUser();
        $idComp = array('idUtilisateur' => $Client);
        $idClient = $repouser->findOneBy($idComp);
        $questions = $repo->findQuestionsByCompetition($comp);


        $score = 0;
        $reponseClient = '[';
        $answeredQuestions = 0;



        foreach ($questions as $question) {
            $userAnswer = $request->get('question_' . $question->getIdQuestion());
            $correctAnswer = $question->getReponseCorrect();
            if ($userAnswer === $correctAnswer) {
                $score++;
                $reponseClient .=  $userAnswer . ',';
            }
            if (!empty($userAnswer)) {
                $answeredQuestions++;
            }
        }

        $competition = $repoComp->findOneBy(['idCompetition' => $comp]);

        // Check if the user has already participated in the competition
        $participants = $competition->getListePaticipants();
        $participantsArray = explode(',', $participants);

        if (in_array($idClient->getIdUtilisateur(), $participantsArray)) {
            $this->addFlash('danger', 'Vous avez déjà participé à cette compétition.');
        } else if ($answeredQuestions !== count($questions)) {
            $this->addFlash('danger', 'Veuillez répondre à toutes les questions avant de soumettre le quiz.');
            return $this->render('competition_front/quiz.html.twig', [
                'questions' => $questions,
                'comp' => $comp,
                'totalQuestions' => count($questions),
                'score' => $score,
            ]);
        } else {
            $idQuiz = $repoQuiz->findOneBy(['idCompetition' => $comp]);
            $reponseClient = rtrim($reponseClient, ',') . ']';

            $resultat = new ResulatQuiz();
            $resultat->setIdClient($idClient);
            $resultat->setIdQuiz($idQuiz);
            $resultat->setScore($score);
            $resultat->setReponseClient($reponseClient);

            $repores->save($resultat, true);

            $participant = '[';
            $competition = $repoComp->findOneBy(['idCompetition' => $comp]);

            $this->addFlash('success', 'votre participation est enregistée avec succés !');
        }


        return $this->redirectToRoute('app_competition_front');
    }
}
