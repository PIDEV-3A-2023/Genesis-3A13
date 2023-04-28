<?php

namespace App\Controller;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReclamationRepository;

#[Route('/reclamations')]
class ReclamationFrontController extends AbstractController
{
    #[Route('/', name: 'app_reclamation_front')]
    public function index(ReclamationRepository $repo): Response
    {
        $reclamations = $repo->findAll();

        return $this->render('reclamation_front/index.html.twig', [
            'reclamations' => $reclamations,
            'controller_name' => 'ReclamationFrontController',
        ]);
    }

    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
    
        $message = null;
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();
    
            $message = 'La Reclamation a ete effectuee avec succes.';
    
            return $this->redirectToRoute('app_reclamation_new', ['message' => $message], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
            'message' => $message,
        ]);
    }
    
}