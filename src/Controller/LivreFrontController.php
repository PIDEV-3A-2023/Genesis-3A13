<?php

namespace App\Controller;

use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/livres')]
class LivreFrontController extends AbstractController
{
  
    #[Route('/trier', name: 'app_livre_trier', methods: ['GET'])]
    public function trier(LivreRepository $livreRepository,PaginatorInterface $paginator,Request $request): Response
    {
        $trie=$livreRepository->trier();
        $livres = $paginator->paginate(
            $trie,
            $request->query->getInt('page', 1), // Current page number, defaults to 1
            10 // Number of items per page
        );
        return $this->render('livre_front/index.html.twig', [
            'livres' => $livres,
      
        ]); }  

    #[Route('/', name: 'app_livre_front')]
    public function index(LivreRepository $repo,PaginatorInterface $paginator,Request $request): Response
    {
        $livres = $repo->findAll();
        $query = $repo->createQueryBuilder('c')
        ->getQuery();

    $livres = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1), // Current page number, defaults to 1
        10 // Number of items per page
    );
        return $this->render('livre_front/index.html.twig', [
            'livres' => $livres,
            'controller_name' => 'LivreFrontController',
        ]);
    }

}
