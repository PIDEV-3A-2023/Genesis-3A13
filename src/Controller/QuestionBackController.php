<?php

namespace App\Controller;

use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/question')]
class QuestionBackController extends AbstractController
{
    #[Route('/quest', name: 'app_question_back')]
    public function index(): Response
    {
        return $this->render('question_back/index.html.twig', [
            'controller_name' => 'QuestionBackController',
        ]);
    }
    #[Route('/search', name: 'app_question_search', methods: ['GET'])]
    public function search(Request $request,QuestionRepository $repo): Response
    {
        $nom = $request->get('nom');
        $resultat = $repo->searchByCompetitionName($nom);

        return $this->render('question/index.html.twig', [
            'questions' => $resultat,
        ]);
    }
    #[Route('/pdf', name: 'app_question_download', methods: ['GET'])]
    public function pdf(QuestionRepository $repo): Response
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

        $questions = $repo->findAll();
        $html=$this->renderView('question/pdf.html.twig', [
            'questions' => $questions,
            'img' => $imgSrc
        ]);
      
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

       
        $pdfData = $pdf->output();

        // Return the PDF as a Response object
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="MaktabtiDashboard-questions.pdf"',
        ];
        
        return new Response($pdfData, 200, $headers);
    }

}
