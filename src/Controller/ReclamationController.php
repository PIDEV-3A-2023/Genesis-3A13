<?php

namespace App\Controller;
use App\Entity\Rating;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReclamationRepository;

#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
    #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager,ReclamationRepository $reclamationRepository): Response
    {
        $reclamations = $entityManager
            ->getRepository(Reclamation::class)
            ->findAll();
            $reclamations = $reclamationRepository->findAllOrderedByFeedback();

        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }

    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        
        
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Handle the image upload
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageData = file_get_contents($imageFile);
                $reclamation->setImage($imageData);
            }
                // Get the current user's ID and set it on the Reclamation entity
            $reclamation->setUser($this->getUser()->getIdUtilisateur());
           
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($reclamation);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }
    

    #[Route('/{idReclamation}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{idReclamation}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{idReclamation}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getIdReclamation(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/search', name: 'app_reclamation_search', methods: ['GET', 'POST'])]
    public function search(Request $request, ReclamationRepository $reclamationRepository): JsonResponse
    {
        $query = $request->get('query');
        $reclamations = $reclamationRepository->findBySearchQuery($query);
    
        $data = [];
        foreach ($reclamations as $reclamation) {
            $data[] = [
                'id' => $reclamation->getIdReclamation(),
                'message' => $reclamation->getMessage(),
                'feedback' => $reclamation->getFeedback(),
                'user' => $reclamation->getUser(),

            ];
        }
    
        return new JsonResponse($data);
    }

}