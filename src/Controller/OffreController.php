<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\Livre;
use App\Entity\Utilisateur;
use App\Entity\Fidelite;
use App\Form\OffreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LivreRepository;
use App\Repository\OffreRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\FideliteRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

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
            $price = $bookId->getPrix();
            $ppp = $form->get('pourcentageSolde')->getData();
            $pourcentageVal = (float) trim($ppp, '%');
            $pourcentageDec = $pourcentageVal / 100;
            $prixAvecSolde = $price * (1 - $pourcentageDec);
            $prixApresSolde = $price - $prixAvecSolde;
            //var_dump($prixApresSolde);
            //die();   
            var_dump($prixApresSolde);
                       
            if ($offret == null) {
                $offre->setPrixSolde($prixApresSolde);

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

    #[Route('/{idOffre}/show', name: 'app_offre_show', methods: ['GET'])]
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

    #[Route('/{idOffre}/delete', name: 'app_offre_delete', methods: ['POST'])]
    public function delete(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getIdOffre(), $request->request->get('_token'))) {
            $entityManager->remove($offre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/sendemail', name: 'envoyer_offre_email')]
    public function sendEmail(FideliteRepository $repfid, Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $qb = $entityManager->createQueryBuilder();

        $resultat = $qb->select('livre.titre, livre.prix, offre.pourcentageSolde, offre.prixSolde')
            ->from(Livre::class, 'livre')
            ->leftJoin(Offre::class, 'offre', 'WITH', 'livre.idLivre = offre.idLivre')
            ->where('offre.idLivre IS NOT NULL')
            ->getQuery()
            ->getResult();
            $text = ""; // initialiser la variable $text avant la boucle


                foreach ($resultat as $res) {
                    $text .= sprintf(" %s %s %s %s \n", $res['titre'], $res['prix'], $res['pourcentageSolde'], $res['prixSolde']);
                }
                       
            
            $clients = $repfid->findAllClients();
            foreach ($clients as $client) {
                $email = (new Email())
                ->from('maktabti10@gmail.com')
                ->to($client->getEmail())
                ->subject('voici les offres disponibles!')
                ->text($text);
                 
            $mailer->send($email);
            
            
        }
        $this->addFlash('success', 'Offre envoyé avec succés!');
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
