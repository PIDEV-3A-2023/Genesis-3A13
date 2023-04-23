<?php

namespace App\Controller;

use App\Entity\Commentaire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EvenementRepository;
use App\Entity\Evenement;
use App\Entity\Reservation;
use App\Entity\Ticket;
use App\Repository\CommentaireRepository;
use App\Repository\ReservationRepository;
use App\Repository\TicketRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;


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
    #[Route('/reservation/{idEvenement}', name: 'reservation')]
    public function reservation(EvenementRepository $repo, $idEvenement)
    {
        $evenement = $repo->findOneBy(array('idEvenement' => $idEvenement));


        return $this->render('evenement_front/reservation.html.twig', ['evenement' => $evenement]);
    }
    #[Route('/ajouterreservation/{idEvenement}', name: 'reservationajout')]
    public function ajoutreservation(EvenementRepository $repo, $idEvenement, Request $request, ReservationRepository $resrepo, UtilisateurRepository $repouser, TicketRepository $repoticket)
    {
        $ticket = new Ticket();
        $ticket->setType('VIP');
        $ticket->setPrix(100);
        $repoticket->save($ticket, true);
        
        $evenement = $repo->findOneBy(array('idEvenement' => $idEvenement));
        $nbticket = $evenement->getNbTicket() - $request->get('nbrTickets');

        $reservation = new Reservation();
        $evenement->setnbticket($nbticket);
        $repo->save($evenement, true);

        $reservation->setIdEvenement($evenement);
        $reservation->setEtat('reserver');
        $reservation->setIdTicket($ticket);
        $resrepo->save($reservation, true);

        $pdfResponse = $this->reserveTicket($evenement);
        return $pdfResponse;


        //$this->addFlash('success', 'reservation est effectué avec succés!');
        //$evenements = $repo->findAll();
        
        //return $this->render('evenement_front/index.html.twig', ['evenements' => $evenements]);
    }
    public function reserveTicket(Evenement $evenement)
    {
       //définir les options
       $pdfOptions = new Options();
       //police par défaut
       $pdfOptions->set('defaultFont', 'Arial');
       $pdfOptions->set('isRemoteEnabled', TRUE);
       $pdfOptions->setChroot('');

       //instancier Dompdf
       $pdf = new Dompdf($pdfOptions);
       $context = stream_context_create([
           'ssl' => [
               'verify_peer' => FALSE,
               'verify_peer_name' => FALSE,
               'allow_self_signed'=> TRUE
           ]
       ]);
       $pdf->setHttpContext($context);
       $img = file_get_contents('Front/images/competition/competition-img.jpg');
       $imgData = base64_encode($img);
       $imgSrc = 'data:image/jpeg;base64,' . $imgData;       

        // Render the ticket template using the generated data
        $html = $this->renderView('evenement_front/ticket.html.twig', [
            'evenement' => $evenement,
            'img'=>$imgSrc
        ]);

        // Generate the PDF from the HTML using dompdf
        $pdf = new Dompdf();
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        // Render the PDF as a string
        $pdfData = $pdf->output();

        // Return the PDF as a Response object
        return new Response($pdfData, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="ticket.pdf"',
        ]);
    }
}
