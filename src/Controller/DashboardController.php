<?php

namespace App\Controller;

use App\Repository\CompetitionRepository;
use App\Repository\ResultatQuizRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(Request $request,CompetitionRepository $repocomp, ResultatQuizRepository $repoResultat): Response
    {

        $competitions = $repocomp->findAll();
        $nbcompetitions = count($competitions);
        $chartData = [];
        $nom = 'nn';
        $chartData = $this->getChartDataForCompetition($nom, $repoResultat);
       /* if ($request->getMethod() === 'POST') {
            $nom = $request->request->get('competition');
   // replace with the competition name you want to display
        
            
        }*/
      

        return $this->render('dashboard/index.html.twig', [
            'nbcompetitions' => $nbcompetitions,
            'competitions' => $competitions,
            'chartData' => json_encode($chartData),
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
}
