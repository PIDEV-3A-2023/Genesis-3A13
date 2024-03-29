<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\CommentaireRepository;
use App\Repository\EvenementRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Knp\Component\Pager\PaginatorInterface;
use Twilio\Rest\Client;


#[Route('/evenement')]
class EvenementController extends AbstractController
{
    #[Route('/', name: 'app_evenement_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator, EvenementRepository $repo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $evenements = $entityManager
            ->getRepository(Evenement::class)
            ->findAll();
        $query = $repo->createQueryBuilder('c')
        
            ->getQuery();

        $evenements = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Current page number, defaults to 1
            10 // Number of items per page
        );

        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenements,
        ]);
    }

    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,EvenementRepository $repo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageData = file_get_contents($imageFile);
                $imageData = $evenement->getNom().'.'.$imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('photos_directory'),
                    $imageData
                );
                $evenement->setImage($imageData);
            }
            $repo->save($evenement, true);
            $entityManager->persist($evenement);
            $entityManager->flush();
            $this->addFlash('success', 'Evenement ajouté avec succés!');

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
            
        ]);
    }

    #[Route('/{idEvenement}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/{idEvenement}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager,EvenementRepository $repo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {

                $imageData = file_get_contents($imageFile);
                $imageData = $evenement->getNom().'.'.$imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('photos_directory'),
                    $imageData
                );
                $evenement->setImage($imageData);
            }
           
            $entityManager->flush();
            $this->addFlash('success', 'Evenement mise à jour avec succés!');

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{idEvenement}', name: 'app_evenement_delete', methods: ['POST'])]
public function delete(UtilisateurRepository $repouser, Request $request, Evenement $evenement, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
{
    $this->denyAccessUnlessGranted('ROLE_ADMIN');
    if ($this->isCsrfTokenValid('delete' . $evenement->getIdEvenement(), $request->request->get('_token'))) {
        $clients = $repouser->getUserWithRole('Client');
        foreach ($clients as $client) {

            $emailMessage = sprintf("Cher Mr/Mme %s %s,\n\nNous sommes vraiment désolé de vous informer que notre evenement %s est annulé. Merci pour votre compréhension.\n\n-- \nMaktabti Application \nNuméro de téléphone : +216 52 329 813 \nAdresse e-mail : maktabti10@gmail.com \nSite web : www.maktabti.com", $client->getNom(), $client->getPrenom(), $evenement->getNom());

            $email = (new Email())
                ->from(new Address('maktabti10@gmail.com', 'Maktabti Application'))
                ->to($client->getEmail())
                ->subject('Evenement annulé!')
                ->text($emailMessage);

            $mailer->send($email);

            $phoneNumber = '+21658412527';
            $sid    = $_ENV['TWILIO_ACCOUNT_SID'];
            $token  = $_ENV['TWILIO_AUTH_TOKEN'];
            $twilio = new Client($sid, $token);

            $message = $twilio->messages->create(
                $phoneNumber, // Numéro de téléphone destinataire
                array(
                    'from' => $_ENV['TWILIO_PHONE_NUMBER'], // Votre numéro Twilio
                    'body' => $emailMessage // Le corps du message SMS
                )
            );

        }
    



            $entityManager->remove($evenement);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Evenement supprimé avec succés!');
        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }
}
