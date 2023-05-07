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
use App\Entity\Utilisateur;
use App\Repository\CommentaireRepository;
use App\Repository\ReservationRepository;
use App\Repository\TicketRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\QrCode;
use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Writer;
use BaconQrCode\Encoder\Encoder;
use Symfony\Component\HttpFoundation\File\File;



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
    ///////////////json get all////////////////
    #[Route('/get', name: 'app_evenement_front_rest')]
    public function index_rest(EvenementRepository $repo): Response
    {
        $evenements = $repo->findAll();
        $data = [];
        // Loop through each competition and convert the image data to a base64-encoded string
        foreach ($evenements as $evenement) {
            
            $image=$evenement->getImage();
            if ($image) {
                $imageResource = $this->getParameter('photos_directory').'/'.$image;
               // $imageResource = $evenement->getImage();
                $imageFile = new File($imageResource);
                // $compressedData = gzcompress($imageString);
                $imageData = base64_encode($imageFile);
                // $encodedData = urlencode($imageData);
                // $imageUrl =  $encodedData;
                $evenement->setImage($imageData);
            }
          /*  if ($evenement->getImage() !== null) {
                $imageResource = $evenement->getImage();
                $imageString = stream_get_contents($imageResource);
                $compressedData = gzcompress($imageString);
                $imageData = base64_encode($compressedData);
                $encodedData = urlencode($imageData);
                $imageUrl =  $encodedData;
            }*/
            $data[] = [
                'idEvenement' => $evenement->getIdEvenement(),
                'idLivre' => $evenement->getIdLivre()->getTitre(),
                'nom' => $evenement->getNom(),
                'lieu' => $evenement->getLieu(),
                'date' => $evenement->getDate(),
                'description' => $evenement->getDescription(),
                'heure'=>$evenement->getHeure(),
                'nbticket'=>$evenement->getNbTicket(),
                'image' => $evenement->getImage(),

            ];
        }

        // Return the JSON response with the modified competition objects
        return $this->json($data, 200, ['Content-Type' => 'application/json']);
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
    //show json
    #[Route('/show/{idEvenement}', name: 'app_evenement_show_rest', methods: ['GET'])]
    public function showRest(Evenement $evenement, CommentaireRepository $repocom): Response
    {
        $data = [];
        $imageData = null;
        
        $image=$evenement->getImage();
        if ($image) {
            $imageResource = $this->getParameter('photos_directory').'/'.$image;
           // $imageResource = $evenement->getImage();
            $imageFile = new File($imageResource);
            // $compressedData = gzcompress($imageString);
            $imageData = base64_encode($imageFile);
            // $encodedData = urlencode($imageData);
            // $imageUrl =  $encodedData;
            $evenement->setImage($imageData);
        }
        $data[] = [
            'idEvenement' => $evenement->getIdEvenement(),
            'idLivre' => $evenement->getIdLivre()->getTitre(),
            'nom' => $evenement->getNom(),
            'lieu' => $evenement->getLieu(),
            'date' => $evenement->getDate(),
            'description' => $evenement->getDescription(),
            'heure'=>$evenement->getHeure(),
            'nbticket'=>$evenement->getNbTicket(),
            'image' => $evenement->getImage(),

        ];

        return $this->json($data, 200, ['Content-Type' => 'application/json']);

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
    ///comment json////////
    #[Route('/commentaire/get/{idEvenement}', name: 'commentaire_rest', methods: ['GET'])]
    public function commenterRest(Evenement $evenement, CommentaireRepository $repocom, Request $request, UtilisateurRepository $repouser, Security $security): Response
    {
        
    
        $commentaires = $repocom->findBy(['idEvenement' => $evenement->getIdEvenement()]);
    $data=[];
        foreach ($commentaires as $commentaire) {
            $data[] = [
                'idCommentaire' => $commentaire->getIdCommentaire(),
                'commentaire' => $commentaire->getCommentaire(),
                'idEvenement' => $commentaire->getIdEvenement()->getNom(),
                'idClient' => $commentaire->getIdClient()->getIdUtilisateur(),
            ];
        }
    
        // Return the comment and the updated comment list as a JSON response
        return $this->json($data);
    }

    #[Route('/commentaire/neww/{idEvenement}', name: 'commentaire_new', methods: ['GET'])]
    public function newcommenter(Evenement $evenement, CommentaireRepository $repocom, Request $request, UtilisateurRepository $repouser, Security $security): Response
    {

        // Extract the required fields from the payload
        $commentaire = $request->query->get("commentaire");
        $idClient = 14;
    
        // Retrieve the user based on the provided $idClient
        $client = $repouser->find($idClient);
       
    
        $comm = new Commentaire();
        $comm->setCommentaire($commentaire);
        $comm->setIdEvenement($evenement);
        $comm->setIdClient($client);
        $repocom->save($comm, true);
    
        return $this->json([
            'success' => 'Commentaire a été créé avec succès!',
        ]);
    }
    /////end comment json/////
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
    public function ajoutreservation(Security $security, EvenementRepository $repo, $idEvenement, Request $request, ReservationRepository $resrepo, UtilisateurRepository $repouser, TicketRepository $repoticket, MailerInterface $mailer)
    {
        $ticket = new Ticket();
        $ticket->setType('VIP');
        $ticket->setPrix(100);
        $repoticket->save($ticket, true);

        $evenement = $repo->findOneBy(array('idEvenement' => $idEvenement));
        if ($evenement->getNbTicket() > 0) {
            $nbticket = $evenement->getNbTicket() - $request->get('nbrTickets');

            $reservation = new Reservation();
            $evenement->setnbticket($nbticket);
            $repo->save($evenement, true);

            $reservation->setIdEvenement($evenement);
            $reservation->setEtat('reserver');
            $reservation->setIdTicket($ticket);
            $resrepo->save($reservation, true);
            // Send an email of confirmation to the connected user
            $Client = $security->getUser();
            $idRes = array('idUtilisateur' => $Client);
            $idClient = $repouser->findOneBy($idRes);
            $email = (new Email())
                ->from(new Address('maktabti10@gmail.com', 'Maktabti Application'))
                ->to($idClient->getEmail())
                ->subject("Confirmation de réservation pour l'événement " . $evenement->getNom())
                ->text(sprintf("Bonjour " . $idClient->getNom() . " " . $idClient->getPrenom() . ",\n" .
                    "\n" .
                    "Nous vous remercions de votre réservation pour l'événement " . $evenement->getNom() . " , qui aura lieu le " . $evenement->getDate()->format('Y-m-d') . " " . $evenement->getHeure()->format('H:i:s') . " à " . $evenement->getLieu() . ". Nous sommes ravis de vous accueillir parmi nous et de vous offrir une expérience mémorable.\n" .
                    "\n" .
                    "Votre réservation a bien été enregistrée, et nous confirmons par la présente que votre place est réservée pour l'événement. Nous vous rappelons que le paiement sera exigé lors de votre arrivée à l'événement.\n" .
                    "\n" . "Vous avez réservé " . $request->get('nbrTickets') . " places pour l'événement.\n" . "\n" .
                    "Si vous avez des questions ou des préoccupations, n'hésitez pas à nous contacter .\n" .
                    "\n" .
                    "Nous avons hâte de vous voir à l'événement !\n" .
                    "\n" .
                    "Cordialement," . "\n\n-- \nMaktabti Application \nNuméro de téléphone : +216 52 329 813 \nAdresse e-mail : maktabti10@gmail.com \nSite web : www.maktabti.com"));


            $mailer->send($email);

            $pdfResponse = $this->reserveTicket($evenement, $ticket, $idClient, $request->get('nbrTickets'));
            return $pdfResponse;
        } else {
            $this->addFlash('danger', 'Tickets indisponible !');
            $evenements = $repo->findAll();

            return $this->render('evenement_front/index.html.twig', ['evenements' => $evenements]);
        }



        //$this->addFlash('success', 'reservation est effectué avec succés!');
        //$evenements = $repo->findAll();

        //return $this->render('evenement_front/index.html.twig', ['evenements' => $evenements]);
    }


    public function reserveTicket(Evenement $evenement, Ticket $ticket, Utilisateur $Client, int $nbticket)
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
                'allow_self_signed' => TRUE
            ]
        ]);
        $pdf->setHttpContext($context);
        $img = file_get_contents('Front/images/logo/maktabti.jpg');
        $imgData = base64_encode($img);
        $imgSrc = 'data:image/jpeg;base64,' . $imgData;



        $qrCodeData =
            $Client->getNom() . " " .
            $Client->getPrenom() . " " .
            $evenement->getIdEvenement() . " " .
            $evenement->getNom() . " " .
            $ticket->getIdTicket();
        //dd($Client->getNom(),$Client->getPrenom(),$evenement->getIdEvenement(),$evenement->getNom(),$ticket->getIdTicket());

        // // Create a new QrCode object
        $qrCode = $qrCodeData;
        $renderer = new Png();
        $renderer->setHeight(100);
        $renderer->setWidth(100);


        // // Create a new writer and generate the QR code image
        $writer = new Writer($renderer);
        $qrCodeImage = $writer->writeString($qrCode);
        $qrCodeDataUri = "data:image/png;base64," . base64_encode($qrCodeImage);


        // Render the ticket template using the generated data
        $html = $this->renderView('evenement_front/ticket.html.twig', [
            'evenement' => $evenement,
            'ticket' => $ticket,
            'img' => $imgSrc,
            'nbticket' => $nbticket,
            'qrCode' => $qrCodeDataUri
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
