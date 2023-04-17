<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Fidelite;
use App\Repository\FideliteRepository;
use App\Repository\UtilisateurRepository;
use App\Entity\Utilisateur;

use Doctrine\ORM\EntityManagerInterface;

#[Route('/fidelitee')]

class FideliteFrontController extends AbstractController
{
    #[Route('/ff', name: 'app_fidelite_front')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $fidelite = $entityManager->getRepository(Fidelite::class)->findOneByIdClient(6);
        $user = $entityManager->getRepository(Utilisateur::class)->find(6);
        return $this->render('fidelite_front/index.html.twig', [
            'fidelite' => $fidelite,
            'user'=> $user,
            'controller_name' => 'FideliteFrontController',
        ]);
    }

}
