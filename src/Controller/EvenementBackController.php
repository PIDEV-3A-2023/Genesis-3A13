<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EvenementRepository;
use Symfony\Component\HttpFoundation\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;

class EvenementBackController extends AbstractController
{
    #[Route('/evenement/back', name: 'app_evenement_back')]
    public function index(): Response
    {
        return $this->render('evenement_back/index.html.twig', [
            'controller_name' => 'EvenementBackController',
        ]);
    }
    #[Route('/search', name: 'search')]
    
    public function evenementsByLocation(Request $request, EvenementRepository $repo,PaginatorInterface $paginator): Response
    {
        $lieu = $request->get('lieu');
        $resultat = $repo->SearchByLieu($lieu);
        $evenements = $paginator->paginate(
            $resultat,
            $request->query->getInt('page', 1), 
            10 
        );

        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenements,
        ]);
    }
    #[Route('/pdf', name: 'app_evenement_download', methods: ['GET'])]
    public function pdf(EvenementRepository $repo): Response
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

        $evenements = $repo->findAll();
        $html=$this->renderView('evenement/pdfevenement.html.twig', [
            'evenements' => $evenements,
            'img' => $imgSrc
        ]);

        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();


        $pdfData = $pdf->output();

        // Return the PDF as a Response object
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="MaktabtiDashboard-evenements.pdf"',
        ];

        return new Response($pdfData, 200, $headers);
    }
}
