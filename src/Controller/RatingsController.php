<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RatingsController extends AbstractController
{
    #[Route('/ratings', name: 'app_ratings')]
    public function index(): Response
    {
        return $this->render('ratings/index.html.twig', [
            'controller_name' => 'RatingsController',
        ]);
    }
}
