<?php

namespace App\Controller;

use App\Repository\CompetitionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
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


