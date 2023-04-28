<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Swift_Message;
use Swift_Mailer;
use Swift_SmtpTransport;

#[Route('/livre')]
class LivreController extends AbstractController
{
    #[Route('/', name: 'app_livre_index', methods: ['GET'])]
    public function index(LivreRepository $livreRepository): Response
    {
        return $this->render('livre/index.html.twig', [
            'livres' => $livreRepository->findAll(),
        ]);
    }
    #[Route('/pdf', name: 'app_livre_download')]
    public function pdf(LivreRepository $repo): Response
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

        $livres = $repo->findAll();
       
        $html=$this->renderView('livre/pdf.html.twig', [
            'livres' => $livres,
            'img' => $imgSrc
        ]);
      
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

       
        $pdfData = $pdf->output();

        // Return the PDF as a Response object
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="MaktabtiDashboard-livres.pdf"',
        ];
        
        return new Response($pdfData, 200, $headers);
    }
    #[Route('/trier', name: 'app_livre_trier', methods: ['GET'])]
    public function trier(LivreRepository $livreRepository): Response
    {
        return $this->render('livre_front/index.html.twig', [
            'livres' => $livreRepository->trier(),
        ]);
    }
    #[Route('/rechercher', name: 'app_livre_recherche', methods: ['GET'])]
    public function rechercher(LivreRepository $livreRepository,Request $request): Response
    {
        $name = $request->get('q');

        return $this->render('livre/index.html.twig', [
            'livres' => $livreRepository->findOneBySomeField($name),
        ]);
    }

    #[Route('/new', name: 'app_livre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LivreRepository $livreRepository): Response
    {
        $livre = new Livre();
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            $imageData = file_get_contents($imageFile);
            $livre->setImage($imageData);
        }
            $livreRepository->save($livre, true);
            $transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
            ->setUsername('recycle.tunisia')
            ->setPassword('ztntffukvpwraygm');
        
        $mailer = new Swift_Mailer($transport);
        
        $message = (new Swift_Message('Nouveau Livre Ajouté'))
            ->setFrom(['recycle.tunisia@gmail.com' => 'Maktabti'])
            ->setTo('saleheddine.ladgham@esprit.tn')
            ->setBody('Livre ajouté avec succes');
            
        
        $mailer->send($message);
            $this->addFlash('success', 'Livre ajouté avec succés!');

            return $this->redirectToRoute('app_livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('livre/new.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }

    #[Route('/{idLivre}', name: 'app_livre_show', methods: ['GET'])]
    public function show(Livre $livre): Response
    {
        return $this->render('livre/show.html.twig', [
            'livre' => $livre,
        ]);
    }

    #[Route('/{idLivre}/edit', name: 'app_livre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livre $livre, LivreRepository $livreRepository): Response
    {
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
               
                $livre->setImage($image);
            }
            
            $livreRepository->save($livre, true);
            $this->addFlash('success', 'Livre mise à jour avec succés!');
            return $this->redirectToRoute('app_livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('livre/edit.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }

    #[Route('/{idLivre}', name: 'app_livre_delete', methods: ['POST'])]
    public function delete(Request $request, Livre $livre, LivreRepository $livreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livre->getIdLivre(), $request->request->get('_token'))) {
            $livreRepository->remove($livre, true);
        }
        $this->addFlash('success', 'Livre supprimé avec succés!');
        
        return $this->redirectToRoute('app_livre_index', [], Response::HTTP_SEE_OTHER);
    }

   

}
