<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OffreRepository;
use App\Repository\LivreRepository;
use App\Entity\Livre;
use App\Entity\Offre;
use Doctrine\ORM\EntityManagerInterface;


#[Route('/Offre')]
class OffreFrontController extends AbstractController
{
    #[Route('/front', name: 'app_offre_front')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $qb = $entityManager->createQueryBuilder();

        $resultat = $qb->select('livre.titre, livre.prix, offre.pourcentageSolde, offre.prixSolde')
            ->from(Livre::class, 'livre')
            ->leftJoin(Offre::class, 'offre', 'WITH', 'livre.idLivre = offre.idLivre')
            ->where('offre.idLivre IS NOT NULL')
            ->getQuery()
            ->getResult();

        return $this->render('offre_front/index.html.twig', [
            'resultat' => $resultat,
            'controller_name' => 'OffreFrontController',
        ]);
    }
}
