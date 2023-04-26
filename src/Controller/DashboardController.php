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


class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(Request $request,LivreRepository $repolivre,UtilisateurRepository $Userrepo,CompetitionRepository $repocomp,CategorieRepository $repocat,EvenementRepository $repoevent, ResultatQuizRepository $repoResultat): Response
    {

        $competitions = $repocomp->findAll();
        $nbcompetitions = count($competitions);
        $chartData = [];
        $categoryData=[];
        $nom = 'nn';
        $chartData = $this->getChartDataForCompetition($nom, $repoResultat);
        $categoryData = $this->categoriesPerBook($repocat);
       /* if ($request->getMethod() === 'POST') {
            $nom = $request->request->get('competition');
   // replace with the competition name you want to display


        
            
        }*/
        $utilisateurs = $Userrepo->findAll();
        $nbutilisateurs = count($utilisateurs);

      $evenements = $repoevent->findAll();
        $nbevenements = count($evenements);

        $livres = $repolivre->findAll();
        $nblivres = count($livres);

        return $this->render('dashboard/index.html.twig', [
            'nbcompetitions' => $nbcompetitions,
            'competitions' => $competitions,
            'chartData' => json_encode($chartData),
            'categoryData' => json_encode($categoryData),
            'nbevenements' => $nbevenements,
            'nblivres' => $nblivres,
            'nbutilisateurs' => $nbutilisateurs,
            'controller_name' => 'DashboardController',
           
        ]);
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
