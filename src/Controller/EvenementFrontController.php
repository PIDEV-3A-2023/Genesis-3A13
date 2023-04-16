<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EvenementRepository;
use App\Entity\Evenement;

#[Route('/evenements')]
class EvenementFrontController extends AbstractController
{
    #[Route('/', name: 'app_evenement_front')]
    public function index(EvenementRepository $repo): Response
    {
        $evenements = $repo->findAll();
        return $this->render('evenement_front/index.html.twig', [
            'evenements' => $evenements,
            'controller_name' => 'EvenementFrontController',
        ]);
    }
    #[Route('/{idEvenement}', name: 'app_evenement_show_front', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
       //$evenement=$repo->findOneBy($idEvenement);
        return $this->render('evenement_front/show.html.twig', [
            'evenement' => $evenement,
            
        ]);
    }
   
}
