<?php

namespace App\Controller;

use App\Entity\Commentaire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EvenementRepository;
use App\Entity\Evenement;
use App\Repository\CommentaireRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;


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
    public function show(Evenement $evenement, CommentaireRepository $repocom): Response
    {
        //$evenement=$repo->findOneBy($idEvenement);
        $commentaires = $repocom->findBy(['idEvenement' => $evenement->getIdEvenement()]);

        return $this->render('evenement_front/show.html.twig', [
            'evenement' => $evenement,
            'commentaires' => $commentaires,

        ]);
    }
    #[Route('/commentaire/{idEvenement}', name: 'commentaire', methods: ['GET'])]
    public function commenter(Evenement $evenement, CommentaireRepository $repocom, Request $request, UtilisateurRepository $repouser, Security $security): Response
    {
        $commentaire = $request->get('commentaire');

        $idClient = $security->getUser(); // Retrieve the user's id

        $commentaires = $repocom->findBy(['idEvenement' => $evenement->getIdEvenement()]);

        $comm = new Commentaire();
        $comm->setCommentaire($commentaire);
        $comm->setIdEvenement($evenement);
        $comm->setIdClient($idClient);
        $repocom->save($comm, true);
        $this->addFlash('success', 'Commentaire a été créé avec succès!');

        return $this->redirectToRoute('app_evenement_show_front', [
            'idEvenement' => $evenement->getIdEvenement(),
            'commentaires' => $commentaires,
        ]);
    }
    #[Route('/commentaire/delete/{idCommentaire}', name: 'app_commentaire_delete')]
    public function deleteCommentaire(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Check if the comment is created by the current logged in user
        //if ($commentaire->getIdClient() == $this->getUser()) {
        if ($this->isCsrfTokenValid('delete' . $commentaire->getIdCommentaire(), $request->request->get('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }
        //}

        return $this->redirectToRoute('app_evenement_show_front', ['idEvenement' => $commentaire->getIdEvenement()->getIdEvenement()]);
    }

    #[Route('/commentaire/edit/{idCommentaire}', name: 'app_commentaire_edit_front')]
    public function editCommentaire(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager, CommentaireRepository $repocom)
    {
        
        $text = $request->get('commentaireedit');
        $commentaire->setCommentaire($text);
        
        $entityManager->flush();

        $this->addFlash('success', 'Votre commentaire a été modifié avec succès!');

        return $this->redirectToRoute('app_evenement_show', ['idEvenement' => $commentaire->getIdEvenement()->getIdEvenement()]);
        

        return $this->render('evenement/show.html.twig', [
            'evenement' => $commentaire->getIdEvenement(),
           
            'commentaire_id' => $commentaire->getIdCommentaire()
        ]);
    }
}
