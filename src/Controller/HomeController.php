<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CompetitionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    #[Route('/Home', name: 'app_home')]
    public function index(CompetitionRepository $repocomp): Response
    {
        $competitions = $repocomp->findAll();
        $nbcompetitions = count($competitions);
        
        return $this->render('front/index.html.twig', [
            'nbcompetitions' => $nbcompetitions,
            
            'message' => 'Welcome to my homepage!',
        ]);
    }
}


