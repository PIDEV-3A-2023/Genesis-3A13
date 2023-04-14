<?php

namespace App\Controller;

use App\Entity\Competition;
use App\Entity\Quiz;
use App\Form\CompetitionType;
use App\Repository\CompetitionRepository;
use App\Repository\QuizRepository;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/competition')]
class CompetitionController extends AbstractController
{
    #[Route('/', name: 'app_competition_index', methods: ['GET'])]
    public function index(CompetitionRepository $repo): Response
    {
        $competitions = $repo->findAll();
            

        return $this->render('competition/index.html.twig', [
            'competitions' => $competitions,
        ]);
    }

    #[Route('/new', name: 'app_competition_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CompetitionRepository $repo, LivreRepository $livreRepo): Response
    {
        $competition = new Competition();
        $form = $this->createForm(CompetitionType::class, $competition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repo->save($competition,true);
              // Create a new Quiz entity and set its competition and livre properties
        $quiz = new Quiz();
        $quiz->setIdCompetition($competition);
        $livre = $livreRepo->findOneByIdLivre($competition->getIdLivre()); // Replace 1 with the ID of the desired book
        $quiz->setIdLivre($livre);

        $em = $this->getDoctrine()->getManager();
        $em->persist($quiz);
        $em->flush();
            $this->addFlash('success', 'Competition ajoutée avec succés!');

            return $this->redirectToRoute('app_competition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('competition/new.html.twig', [
            'competition' => $competition,
            'form' => $form,
        ]);
    }

    #[Route('/{idCompetition}', name: 'app_competition_show', methods: ['GET'])]
    public function show(Competition $competition): Response
    {
        return $this->render('competition/show.html.twig', [
            'competition' => $competition,
        ]);
    }

    #[Route('/{idCompetition}/edit', name: 'app_competition_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Competition $competition, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CompetitionType::class, $competition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Competition est mise à jour avec succés!');

            return $this->redirectToRoute('app_competition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('competition/edit.html.twig', [
            'competition' => $competition,
            'form' => $form,
        ]);
    }

    #[Route('/{idCompetition}', name: 'app_competition_delete', methods: ['POST'])]
    public function delete(Request $request, Competition $competition, CompetitionRepository $repo, QuizRepository $quizRepo): Response
    {
        if ($this->isCsrfTokenValid('delete' . $competition->getIdCompetition(), $request->request->get('_token'))) {
            $now = new \DateTime();
        if ($now >= $competition->getDateDebut() && $now <= $competition->getDateFin()) {
            $this->addFlash('danger', 'Impossible de supprimer la compétition car elle est déja ouverte !');
        } else {
            $quiz = $quizRepo->findOneBy(['idCompetition' => $competition->getIdCompetition()]);
            if ($quiz) {
                $this->addFlash('danger', 'Impossible de supprimer la compétition vous devez d\'abord supprimer la quiz!');
            } else {
                $repo->remove($competition, true);
                $this->addFlash('success', 'Compétition supprimée avec succès!');
            }
        }
         
        }
        
        return $this->redirectToRoute('app_competition_index', [], Response::HTTP_SEE_OTHER);
    }
}
