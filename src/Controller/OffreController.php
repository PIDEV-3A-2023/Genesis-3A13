<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\Livre;

use App\Form\OffreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LivreRepository;
use App\Repository\OffreRepository;

#[Route('/offre')]
class OffreController extends AbstractController
{
    #[Route('/', name: 'app_offre_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $offres = $entityManager
            ->getRepository(Offre::class)
            ->findAll();

        return $this->render('offre/index.html.twig', [
            'offres' => $offres,
        ]);
    }

    #[Route('/new', name: 'app_offre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,OffreRepository $repo): Response
    {
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);
        $offres=$repo->findAll();
        $offresIds = array_column($offres, 'idLivre');

        if ($form->isSubmitted() && $form->isValid()) {
            $bookId = $form->get('idLivre')->getData();
            $offret = $repo->findOneBy(['idLivre' => $bookId]);

            if ($offret == null) {
                    
                $entityManager->persist($offre);
                $entityManager->flush();
                $this->addFlash('success', 'offre ajouter avec succés.'.implode(', ', $offresIds));
            } else {
                var_dump('test');
                $this->addFlash('danger', 'le livre slectionner a deja un offre.');
            }

            return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offre/new.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/{idOffre}', name: 'app_offre_show', methods: ['GET'])]
    public function show(Offre $offre): Response
    {
        return $this->render('offre/show.html.twig', [
            'offre' => $offre,
        ]);
    }

    #[Route('/{idOffre}/edit', name: 'app_offre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/{idOffre}', name: 'app_offre_delete', methods: ['POST'])]
    public function delete(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getIdOffre(), $request->request->get('_token'))) {
            $entityManager->remove($offre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/calcul-prix-solde/${prixInit}/${pourcentage}')]
    public function calculprixsolde(float $prixinit ,string $pourcentage) : float
    {
        $pourcentageVal = (float) trim($pourcentage, '%');
        $pourcentageDec = $pourcentageVal / 100;
        $prixAvecSolde = $prixInit * (1 - $pourcentageDec);
        $prixApresSolde = $prixInit - $prixAvecSolde;
        return $prixApresSolde;

    }
    
    #[Route('/findbyid/${idLivre}', methods: ['GET'])]
    public function getPrixLivre($idLivre,LivreRepository $repo) : float
    {
        $Livre = $repo->find($idLivre);

       $prix=$Livre->getPrix();
        return $prix;
    }

  


}
