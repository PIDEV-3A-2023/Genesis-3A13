<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\CompetitionRepository;
use App\Repository\EvenementRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\LivreRepository;
use App\Repository\ResultatQuizRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;


class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]

    public function index(Request $request,LivreRepository $repolivre,UtilisateurRepository $Userrepo,CompetitionRepository $repocomp,CategorieRepository $repocat,EvenementRepository $repoevent, ResultatQuizRepository $repoResultat): Response

    {

        $competitions = $repocomp->findAll();
        $nbcompetitions = count($competitions);
        $chartData = [];
        $categoryData = [];
        $nom = 'nn';
        $chartData = $this->getChartDataForCompetition($nom, $repoResultat);
        $categoryData = $this->categoriesPerBook($repocat);


        $competitionssemaine =$repocomp->findCompetitionsOpenedThisWeek();

    
        $utilisateurs = $Userrepo->findAll();
        $nbutilisateurs = count($utilisateurs);

        $Admins=$Userrepo->getUserWithRole("Administrateur");
        $nbadmins = count($Admins);

        $Auteurs=$Userrepo->getUserWithRole("Auteur");
        $nbaauteurs = count($Auteurs);

        $Clients=$Userrepo->getUserWithRole("Client");
        $nbaclients = count($Clients);
        

      $evenements = $repoevent->findAll();
        $nbevenements = count($evenements);

        $livres = $repolivre->findAll();
        $nblivres = count($livres);


        return $this->render('dashboard/index.html.twig', [
            'nbcompetitions' => $nbcompetitions,
            'competitions' => $competitions,
            'competitionssemaine' => $competitionssemaine,
            'chartData' => json_encode($chartData),
            'categoryData' => json_encode($categoryData),
            'nbevenements' => $nbevenements,
            'nblivres' => $nblivres,
            'nbutilisateurs' => $nbutilisateurs,
            'controller_name' => 'DashboardController',

        ]);
    }
    #[Route('/mailwinner', name: 'mail_winner')]
    public function sendMails(CompetitionRepository $repocomp, MailerInterface $mailer,UtilisateurRepository $repouser){

        $gagnants =$repocomp->findCompetitionsOpenedThisWeekWithWinnersAndRewards();
        foreach ($gagnants as $gagnant){
            $client = $repouser->findOneBy(array('idUtilisateur' =>$gagnant['winner_client_id']));
            $email = (new Email())
                ->from(new Address('maktabti10@gmail.com', 'Maktabti Application'))
                ->to($client->getEmail())
                ->subject("Félicitations pour votre victoire au quiz de ". $gagnant['competition_name'] ." et livraison de votre prix")
                ->text("Mme/Mr ".$client->getPrenom()." ".$client->getNom().",\n" .
                "\n" .
                "Nous sommes ravis de vous informer que vous avez remporté la compétition". $gagnant['competition_name']." organisée par notre entreprise."
                        . " Votre participation a été exceptionnelle et nous tenons à vous féliciter pour votre victoire.\n" .
                "\n" .
                "En reconnaissance de votre succès, nous sommes heureux de vous offrir ".$gagnant['competition_reward'].", "
                        . "qui sera livré chez vous dans les prochains jours. Notre équipe de livraison prendra contact avec vous pour organiser "
                        . "la livraison à l'adresse que vous nous avez fournie.\n" .
                "\n" .
                "Nous espérons que vous serez ravi(e) de recevoir votre prix et que vous en profiterez pleinement."
                        . " Nous tenons également à vous remercier de votre participation à notre quiz et espérons que vous avez apprécié l'expérience autant que nous.\n" .
                "\n" .
                "Félicitations encore pour votre réussite et merci de votre engagement envers notre entreprise.\n" .
                "\n" .
                "Cordialement,\n\n". "\n\n-- \nMaktabti Application \nNuméro de téléphone : +216 52 329 813 \nAdresse e-mail : maktabti10@gmail.com \nSite web : www.maktabti.com");

            $mailer->send($email);
            $this->addFlash('success','Emails Envoyé avec succés!'.$client->getEmail());
        }
       
       
        return $this->redirectToRoute('app_dashboard');
    }
    
    /**
     * @Route("/chart-data", name="chart_data")
     */
    public function chartData(Request $request, ResultatQuizRepository $repoResultat): JsonResponse
    {
        $nom = $request->query->get('nom');
        $chartData = $this->getChartDataForCompetition($nom, $repoResultat);
        return new JsonResponse($chartData);
    }

    public function getChartDataForCompetition($nom, ResultatQuizRepository $repoResultat)
    {

        $results = $repoResultat->getRsultbyCompetitionName($nom);

        $data = [];
        foreach ($results as $result) {
            $clientName = $result->getIdClient()->getNom() . " " . $result->getIdClient()->getPrenom(); // replace with the name property of your client entity
            $score = $result->getScore();

            if (!isset($data[$clientName])) {
                $data[$clientName] = 0;
            }
            $data[$clientName] += $score;
        }

        $chartData = [
            'labels' => array_keys($data),
            'datasets' => [
                [
                    'label' => 'Score',
                    'data' => array_values($data),
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                    ],
                    'borderColor' => [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
        ];
        return $chartData;
    }






    /**
     * @Route("/categories-per-book", name="categories_per_book")
     */
    public function categoriesPerBook(CategorieRepository $repocat)
    {
        $categoryData = $repocat->getCatgoriePerBook();

        // Format the data for use in Chart.js
        $chartData = [
            'labels' => [],
            'data' => [],
            'colors' => []
        ];
        $totalBooks = 0;
        foreach ($categoryData as $data) {
            $totalBooks += $data['count'];
        }

        foreach ($categoryData as $data) {
            $chartData['labels'][] = $data['category'];
            $percentage = round(($data['count'] / $totalBooks) * 100);
            $chartData['data'][] = $percentage;
            $chartData['colors'][] = $this->getRandomColor();
        }

        // Render the template with the chart data
        return $chartData;
    }

    private function getRandomColor()
    {
        $letters = '0123456789ABCDEF';
        $color = '#';
        for ($i = 0; $i < 6; $i++) {
            $color .= $letters[rand(0, 15)];
        }
        return $color;
    }
}
