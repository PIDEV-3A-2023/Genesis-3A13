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
    public function show(Evenement $evenement,CommentaireRepository $repocom): Response
    {
       //$evenement=$repo->findOneBy($idEvenement);
       $commentaires = $repocom->findBy(['idEvenement' => $evenement->getIdEvenement()]);

        return $this->render('evenement_front/show.html.twig', [
            'evenement' => $evenement,
            'commentaires' => $commentaires,
            
        ]);
    }
    #[Route('/commentaire/{idEvenement}', name: 'commentaire', methods: ['GET'])]
public function commenter(Evenement $evenement, CommentaireRepository $repocom, Request $request, UtilisateurRepository $repouser): Response
{
    $commentaire = $request->get('commentaire');
    $Client = 3;
    $idCom = array('idUtilisateur' => $Client);
    $idClient = $repouser->findOneBy($idCom);

    $commentaires = $repocom->findBy(['idEvenement' => $evenement->getIdEvenement()]);

    $comm = new Commentaire();
    $comm->setCommentaire($commentaire);
    $comm->setIdEvenement($evenement);
    $comm->setIdClient($idClient);
    $repocom->save($comm, true);
    $this->addFlash('success', 'Commentaire a été créé avec succès!');

    return $this->redirectToRoute('app_evenement_show_front', ['idEvenement' => $evenement->getIdEvenement(),'commentaires' => $commentaires,]);
}
// #[Route('/{idCommentaire}', name: 'delete', methods: ['POST'])]
//     public function delete(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository): Response
//     {
//         if ($this->isCsrfTokenValid('delete'.$commentaire->getIdCommentaire(), $request->request->get('_token'))) {
//             $commentaireRepository->remove($commentaire, true);
//         }

//         return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
//     }
   
}
