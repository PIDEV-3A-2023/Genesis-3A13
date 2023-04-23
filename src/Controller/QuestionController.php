<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/question')]
class QuestionController extends AbstractController
{
    #[Route('/', name: 'app_question_index', methods: ['GET'])]
    public function index(Request $request,QuestionRepository $repo,PaginatorInterface $paginator): Response
    {
        $query = $repo->createQueryBuilder('q')
        ->getQuery();

    $questions = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1), // Current page number, defaults to 1
        10 // Number of items per page
    );
        return $this->render('question/index.html.twig', [
            'questions' => $questions,
        ]);
    }

    #[Route('/new', name: 'app_question_new', methods: ['GET', 'POST'])]
    public function new(Request $request, QuestionRepository $questionRepository): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $questionRepository->save($question, true);
            $this->addFlash('success', 'Question ajoutée avec succés!');
            return $this->redirectToRoute('app_question_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('question/new.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }

    #[Route('/{idQuestion}', name: 'app_question_show', methods: ['GET'])]
    public function show(Question $question): Response
    {
        return $this->render('question/show.html.twig', [
            'question' => $question,
        ]);
    }

    #[Route('/{idQuestion}/edit', name: 'app_question_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Question $question, QuestionRepository $questionRepository): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $questionRepository->save($question, true);
            $this->addFlash('success', 'Question modifiée avec succés!');
            return $this->redirectToRoute('app_question_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('question/edit.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }

    #[Route('/{idQuestion}', name: 'app_question_delete', methods: ['POST'])]
    public function delete(Request $request, Question $question, QuestionRepository $questionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getIdQuestion(), $request->request->get('_token'))) {
            $questionRepository->remove($question, true);
        }
        $this->addFlash('success', 'Question supprimée avec succés!');
        return $this->redirectToRoute('app_question_index', [], Response::HTTP_SEE_OTHER);
    }
}
