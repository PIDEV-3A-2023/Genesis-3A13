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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;

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
    public function participate(Request $request, QuestionRepository $repo, $comp, UtilisateurRepository $repouser, ResultatQuizRepository $repores, QuizRepository $repoQuiz, competitionRepository $repoComp, Security $security, MailerInterface $mailer): Response
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
            $reponseClient .=  $userAnswer . ',';
            if ($userAnswer === $correctAnswer) {
                $score++;
                
            }
            if (!empty($userAnswer)) {
                $answeredQuestions++;
            }
        }

        $competition = $repoComp->findOneBy(['idCompetition' => $comp]);

        // Check if the user has already participated in the competition
        $participants = $competition->getListePaticipants();
        $participantsArray = json_decode($participants, true); // On utilise json_decode pour convertir la liste de participants en tableau associatif

        if (is_array($participantsArray) && in_array($idClient->getIdUtilisateur(), $participantsArray)) {
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



            // Check if the user has already participated in the competition
            $participants = $competition->getListePaticipants();



            $participantsArray = '[' . $idClient->getIdUtilisateur() . ']' . '' . $participants;


            // Update the competition with the new list of participants
            $competition->setListePaticipants($participantsArray);
            $repoComp->save($competition, true);

            $email = (new Email())
            ->from(new Address('maktabti10@gmail.com', 'Maktabti Application'))
            ->to($idClient->getEmail())
            ->subject("Confirmation de participation a la compétition : ".$competition->getNom())
            ->text("Cher/Chère ".$idClient->getNom()." ".$idClient->getPrenom().",\n" .
            "\n" .
            "Nous tenons à vous remercier d'avoir participé à notre compétition. "
                             . "Nous sommes ravis que vous ayez décidé de participer et nous espérons que vous avez trouvé l'expérience enrichissante.\n" .
            "\n" .
            "Nous sommes heureux de vous informer que vos réponses ont été enregistrées avec succès. "
                             . "Nous avons reçu votre formulaire de réponse et nous sommes impatients de vous donner les résultats dès que possible."
                             . " Nous vous contacterons dès que nous aurons terminé d'évaluer toutes les réponses.\n" .
            "\n" .
            "Encore une fois, merci d'avoir participé et d'avoir montré votre intérêt pour notre entreprise. "
                             . "Nous espérons que vous avez apprécié l'expérience et nous sommes impatients de vous proposer d'autres activités intéressantes à l'avenir.\n" .
            "\n" .
            "Sincèrement,\n"."\n". "\n\n-- \nMaktabti Application \nNuméro de téléphone : +216 52 329 813 \nAdresse e-mail : maktabti10@gmail.com \nSite web : www.maktabti.com");

        $mailer->send($email);



            $this->addFlash('success', 'votre participation est enregistée avec succés !');
        }


        return $this->redirectToRoute('app_competition_front');
    }
}
