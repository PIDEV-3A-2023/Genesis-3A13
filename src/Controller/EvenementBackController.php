<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EvenementRepository;
use Symfony\Component\HttpFoundation\Request;

class EvenementBackController extends AbstractController
{
    #[Route('/evenement/back', name: 'app_evenement_back')]
    public function index(): Response
    {
        return $this->render('evenement_back/index.html.twig', [
            'controller_name' => 'EvenementBackController',
        ]);
    }
    #[Route('/search', name: 'search')]
    
    public function evenementsByLocation(Request $request, EvenementRepository $repo): Response
    {
        $lieu = $request->get('lieu');
        $resultat = $repo->SearchByLieu($lieu);

        return $this->render('evenement/index.html.twig', [
            'evenements' => $resultat,
        ]);
    }
}
