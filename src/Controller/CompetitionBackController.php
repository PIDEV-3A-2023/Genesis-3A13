<?php


namespace App\Controller;
require_once __DIR__.'/../../vendor/autoload.php';


use App\Entity\Competition;
use App\Repository\CompetitionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\DompdfOptions as Options;


#[Route('/competition')]
class CompetitionBackController extends AbstractController
{
    #[Route('/com', name: 'app_competition_back')]
    public function index(): Response
    {
        return $this->render('competition_back/index.html.twig', [
            'controller_name' => 'CompetitionBackController',
        ]);
    }
    #[Route('/search', name: 'app_competition_search', methods: ['GET'])]
    public function search(Request $request,CompetitionRepository $repo): Response
    {
        $nom = $request->get('nom');
        $resultat = $repo->SearchByNom($nom);

        return $this->render('competition/index.html.twig', [
            'competitions' => $resultat,
        ]);
    }
   /* #[Route('/download', name: 'app_competition_download', methods: ['GET'])]
    public function download(Request $request,CompetitionRepository $repo): Response
    {
        //définir les options
        $pdfOptions = new Options();
        //police par défaut
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled', TRUE);

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

        //générer le html
        $competitions = $repo->findAll();
        $html=$this->renderView('competition/download.html.twig', [
            'competitions' => $competitions,
        ]);

        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        //générer nom de fichier
        $fichier = 'maktabti-competitions.pdf';
        
        //envoyer le fichier
        $pdf->stream($fichier, [
            "Attachment" => true
        ]);
        return new Response();
    }*/

}
