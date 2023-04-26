<?php

namespace App\Controller;
use App\Repository\UtilisateurRepository;
use App\Repository\CommentaireRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CompetitionRepository;
use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    
    public function index(CompetitionRepository $repocomp,UtilisateurRepository $Userrepo,EvenementRepository $repoevent,CommentaireRepository $repocomm): Response
    {
        $competitions = $repocomp->findAll();
        $nbcompetitions = count($competitions);

        $evenements = $repoevent->findAll();
        $nbevenements = count($evenements);
        $comments= $this->comments($repocomm);

        $utilisateurs = $Userrepo->findAll();
        $nbutilisateurs = count($utilisateurs);
        
        return $this->render('front/index.html.twig', [
            'nbcompetitions' => $nbcompetitions,
            'nbevenements' => $nbevenements,
            'nbutilisateurs' => $nbutilisateurs,
            'comments' => $comments,
            'message' => 'Welcome to my homepage!',
        ]);
    }

    public function comments(CommentaireRepository $repocomm){
        $comments = $repocomm->findRandomComments();
        return $comments;
    }
}


