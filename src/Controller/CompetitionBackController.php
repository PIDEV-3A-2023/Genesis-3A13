<?php


namespace App\Controller;


use App\Entity\Competition;
use App\Repository\CompetitionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;


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
    #[Route('/pdf', name: 'app_competition_download', methods: ['GET'])]
    public function pdf(CompetitionRepository $repo): Response
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

        //générer le html
        $img = file_get_contents('Front/images/logo/maktabti.jpg');
        $imgData = base64_encode($img);
        $imgSrc = 'data:image/jpeg;base64,' . $imgData;

        $competitions = $repo->findAll();
        $html=$this->renderView('competition/pdf.html.twig', [
            'competitions' => $competitions,
            'img' => $imgSrc
        ]);
      
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

       
        $pdfData = $pdf->output();

        // Return the PDF as a Response object
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="MaktabtiDashboard-competitions.pdf"',
        ];
        
        return new Response($pdfData, 200, $headers);
    }

}
