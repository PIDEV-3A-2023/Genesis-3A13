<?php

namespace App\Controller;
use App\Entity\Panierlivre;
use App\Form\PanierlivreType;
use App\Repository\PanierlivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/panierlivre')]
class PanierlivreController extends AbstractController
{
    #[Route('/', name: 'app_panierlivre_index', methods: ['GET'])]
    public function index(PanierlivreRepository $panierlivreRepository): Response
    {
        return $this->render('panierlivre/index.html.twig', [
            'panierlivres' => $panierlivreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_panierlivre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PanierlivreRepository $panierlivreRepository): Response
    {
        $panierlivre = new Panierlivre();
        $form = $this->createForm(PanierlivreType::class, $panierlivre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $panierlivreRepository->save($panierlivre, true);

            return $this->redirectToRoute('app_panierlivre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('panierlivre/new.html.twig', [
            'panierlivre' => $panierlivre,
            'form' => $form,
        ]);
    }

    #[Route('/{idPanierLivre}', name: 'app_panierlivre_show', methods: ['GET'])]
    public function show(Panierlivre $panierlivre): Response
    {
        return $this->render('panierlivre/show.html.twig', [
            'panierlivre' => $panierlivre,
        ]);
    }

    #[Route('/{idPanierLivre}/edit', name: 'app_panierlivre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Panierlivre $panierlivre, PanierlivreRepository $panierlivreRepository): Response
    {
        $form = $this->createForm(PanierlivreType::class, $panierlivre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $panierlivreRepository->save($panierlivre, true);

            return $this->redirectToRoute('app_panierlivre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('panierlivre/edit.html.twig', [
            'panierlivre' => $panierlivre,
            'form' => $form,
        ]);
    }

    #[Route('/{idPanierLivre}', name: 'app_panierlivre_delete', methods: ['POST'])]
    public function delete(Request $request, Panierlivre $panierlivre, PanierlivreRepository $panierlivreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panierlivre->getIdPanierLivre(), $request->request->get('_token'))) {
            $panierlivreRepository->remove($panierlivre, true);
        }

        return $this->redirectToRoute('app_panierlivre_index', [], Response::HTTP_SEE_OTHER);
    }
}
