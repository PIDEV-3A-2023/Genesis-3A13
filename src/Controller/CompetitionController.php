<?php

namespace App\Controller;

use App\Entity\Competition;
use App\Entity\Quiz;
use App\Form\CompetitionType;
use App\Repository\CompetitionRepository;
use App\Repository\QuizRepository;
use App\Repository\LivreRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;

#[Route('/competition')]
class CompetitionController extends AbstractController
{
    #[Route('/', name: 'app_competition_index', methods: ['GET'])]
    public function index(Request $request, CompetitionRepository $repo, PaginatorInterface $paginator): Response
    {
        $query = $repo->createQueryBuilder('c')
            ->getQuery();

        $competitions = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Current page number, defaults to 1
            10 // Number of items per page
        );


        return $this->render('competition/index.html.twig', [
            'competitions' => $competitions,
        ]);
    }

    #[Route('/new', name: 'app_competition_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CompetitionRepository $repo, LivreRepository $livreRepo, MailerInterface $mailer): Response
    {
        $competition = new Competition();
        $form = $this->createForm(CompetitionType::class, $competition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageData = file_get_contents($imageFile);
                $competition->setImage($imageData);
            }
            $repo->save($competition, true);

            $quiz = new Quiz();
            $quiz->setIdCompetition($competition);
            $livre = $livreRepo->findOneByIdLivre($competition->getIdLivre()); // Replace 1 with the ID of the desired book
            $quiz->setIdLivre($livre);

            $em = $this->getDoctrine()->getManager();
            $em->persist($quiz);
            $em->flush();

            $auteur = $livre->getIdAuteur();

            $email = (new Email())
                ->from(new Address('maktabti10@gmail.com', 'Maktabti Application'))
                ->to($auteur->getEmail())
                ->subject("Votre livre " . $livre->getTitre() . " a été sélectionné pour participer au concours de quiz!")
                ->text("Cher/chère " . $auteur->getNom() . " " . $auteur->getPrenom() . ",\n" .
                    "\n" .
                    "Nous sommes ravis de vous annoncer que votre livre " . $livre->getTitre() . " a été sélectionné pour participer à notre concours de quiz " . $competition->getNom() . "."
                    . " Nous sommes très impressionnés par la qualité de votre livre et nous sommes convaincus qu'il aura beaucoup de succès lors de la compétition.\n" .
                    "\n" .
                    "Le concours commencera le " . $competition->getDatedebut()->format('Y-m-d') . " et se terminera le " . $competition->getDatefin()->format('Y-m-d') . ". Les participants devront répondre à des questions concernant "
                    . "votre livre pour avoir une chance de gagner de superbes prix.\n" .
                    "\n" .
                    "Nous tenons à vous remercier pour votre travail acharné et votre engagement envers l'excellence littéraire."
                    . " Nous sommes convaincus que votre livre inspirera de nombreux lecteurs à participer au concours.\n" .
                    "\n" .
                    "Nous vous souhaitons bonne chance et sommes impatients de voir votre livre briller lors de la compétition.\n" .
                    "\n" .
                    "Sincèrement, \n" . "\n" . "\n\n-- \nMaktabti Application \nNuméro de téléphone : +216 52 329 813 \nAdresse e-mail : maktabti10@gmail.com \nSite web : www.maktabti.com");

            $mailer->send($email);

            $this->addFlash('success', 'Competition ajoutée avec succés!');

            return $this->redirectToRoute('app_competition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('competition/new.html.twig', [
            'competition' => $competition,
            'form' => $form,
        ]);
    }

    #[Route('/{idCompetition}', name: 'app_competition_show', methods: ['GET'])]
    public function show(Competition $competition): Response
    {
        return $this->render('competition/show.html.twig', [
            'competition' => $competition,
        ]);
    }

    #[Route('/{idCompetition}/edit', name: 'app_competition_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Competition $competition, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CompetitionType::class, $competition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
               
                $competition->setImage($image);
            }
            $entityManager->flush();
            $this->addFlash('success', 'Competition est mise à jour avec succés!');

            return $this->redirectToRoute('app_competition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('competition/edit.html.twig', [
            'competition' => $competition,
            'form' => $form,
        ]);
    }

    #[Route('/{idCompetition}', name: 'app_competition_delete', methods: ['POST'])]
    public function delete(Request $request, Competition $competition, CompetitionRepository $repo, QuizRepository $quizRepo): Response
    {
        if ($this->isCsrfTokenValid('delete' . $competition->getIdCompetition(), $request->request->get('_token'))) {
            $now = new \DateTime();
            if ($now >= $competition->getDateDebut() && $now <= $competition->getDateFin()) {
                $this->addFlash('danger', 'Impossible de supprimer la compétition car elle est déja ouverte !');
            } else {
                $quiz = $quizRepo->findOneBy(['idCompetition' => $competition->getIdCompetition()]);
                if ($quiz) {
                    $this->addFlash('danger', 'Impossible de supprimer la compétition vous devez d\'abord supprimer la quiz!');
                } else {
                    $repo->remove($competition, true);
                    $this->addFlash('success', 'Compétition supprimée avec succès!');
                }
            }
        }

        return $this->redirectToRoute('app_competition_index', [], Response::HTTP_SEE_OTHER);
    }
}
