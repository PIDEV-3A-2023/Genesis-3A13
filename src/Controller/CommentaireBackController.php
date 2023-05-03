<?php

namespace App\Controller;

use App\Repository\CommentaireRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/commentaire')]
class CommentaireBackController extends AbstractController
{
    #[Route('/commentaire/back', name: 'app_commentaire_back')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('commentaire_back/index.html.twig', [
            'controller_name' => 'CommentaireBackController',
        ]);
    }
    #[Route('/search', name: 'searchcommentaire')]
    
    public function commentairesByNom(Request $request, CommentaireRepository $repo,PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $nom = $request->get('nom');
        $resultat = $repo->SearchByNom($nom);
        $commentaires = $paginator->paginate(
            $resultat,
            $request->query->getInt('page', 1), 
            10 
        );

        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaires,
        ]);
    }
}
