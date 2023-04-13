<?php

namespace App\Controller;

use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/livres')]
class LivreFrontController extends AbstractController
{
    #[Route('/', name: 'app_livre_front')]
    public function index(LivreRepository $repo): Response
    {
        $livres = $repo->findAll();
        return $this->render('livre_front/index.html.twig', [
            'livres' => $livres,
            'controller_name' => 'LivreFrontController',
        ]);
    }
}
