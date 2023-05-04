<?php

namespace App\Controller;
use App\Repository\UtilisateurRepository;
use App\Repository\CommentaireRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CompetitionRepository;
use App\Repository\EvenementRepository;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    
    public function index(CompetitionRepository $repocomp,UtilisateurRepository $Userrepo,EvenementRepository $repoevent,CommentaireRepository $repocomm,LivreRepository $repolivre): Response
    {
        $competitions = $repocomp->findAll();
        $nbcompetitions = count($competitions);

        $evenements = $repoevent->findAll();
        $nbevenements = count($evenements);
        $livresnb = $repolivre->findAll();
        $nblivres = count($livresnb);
        

        // Call the event function from EvenementRepository to get the last 3 events
        $lastEvents = $repoevent->event();
        $livres=$repolivre->livrehome();

        $utilisateurs = $Userrepo->findAll();
        $nbutilisateurs = count($utilisateurs);
        
        return $this->render('front/index.html.twig', [
            'nbcompetitions' => $nbcompetitions,
            'nbevenements' => $nbevenements,
            'nbutilisateurs' => $nbutilisateurs,
            'livres'=>$livres,
            'nblivres'=>$nblivres,
            'lastEvents' => $lastEvents, // Pass the last 3 events to the template
            'message' => 'Welcome to my homepage!',
        ]);
    }

   
}


