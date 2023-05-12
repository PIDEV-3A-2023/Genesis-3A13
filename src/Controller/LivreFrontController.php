<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/livres')]
class LivreFrontController extends AbstractController
{
  
    #[Route('/trier', name: 'app_livre_trier', methods: ['GET'])]
    public function trier(LivreRepository $livreRepository,PaginatorInterface $paginator,Request $request): Response
    {
        $trie=$livreRepository->trier();
        $livres = $paginator->paginate(
            $trie,
            $request->query->getInt('page', 1), // Current page number, defaults to 1
            10 // Number of items per page
        );
        return $this->render('livre_front/index.html.twig', [
            'livres' => $livres,
      
        ]); }  

    #[Route('/', name: 'app_livre_front')]
    public function index(LivreRepository $repo,PaginatorInterface $paginator,Request $request): Response
    {
        $livres = $repo->findAll();
        $query = $repo->createQueryBuilder('c')
        ->getQuery();

    $livres = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1), // Current page number, defaults to 1
        10 // Number of items per page
    );
        return $this->render('livre_front/index.html.twig', [
            'livres' => $livres,
            'controller_name' => 'LivreFrontController',
        ]);
    }
    #[Route('/get', name: 'app_livre_front_rest')]
    public function indexm(LivreRepository $repo): Response
    {
        $livres = $repo->findAll();
        $data = [];
        // Loop through each competition and convert the image data to a base64-encoded string
        foreach ($livres as $livre) {

         
            $data[] = [
                'idLivre' => $livre->getIdlivre(),
                'idcategorie' => $livre->getIdCategorie()->getNom(),
                'idAuteur' => $livre->getIdAuteur()->getNom()." ".$livre->getIdAuteur()->getPrenom(),
                'titre' => $livre->getTitre(),
                'datePub' => $livre->getDatePub()? $livre->getDatePub()->format('Y-m-d') : null,
                'langue' => $livre->getLangue(),
                'isbn' => $livre->getisbn(),
                'nbPages' => $livre->getNbPages(),
                'resume' => $livre->getresume(),
                'prix' => $livre->getprix(),
              //  'image' => $livres->getImage(),

            ];
        }

        // Return the JSON response with the modified competition objects
        return $this->json($data, 200, ['Content-Type' => 'application/json']);
    }
    #[Route('/get/{idLivre}', name: 'app_livre_show_rest', methods: ['GET'])]
    public function showRest(Livre $livre): Response
    {

        $data = [];
       
        $data[] = [
            'idLivre' => $livre->getIdlivre(),
            'idcategorie' => $livre->getIdCategorie()->getNom(),
            'idAuteur' => $livre->getIdAuteur()->getNom()." ".$livre->getIdAuteur()->getPrenom(),
            'titre' => $livre->getTitre(),
            'datePub' => $livre->getDatePub()? $livre->getDatePub()->format('Y-m-d') : null,
            'langue' => $livre->getLangue(),
            'isbn' => $livre->getisbn(),
            'nbPages' => $livre->getNbPages(),
            'resume' => $livre->getresume(),
            'prix' => $livre->getprix(),
            //'image' => $livre->getImage(),

        ];

        return $this->json($data, 200, ['Content-Type' => 'application/json']);
    }
    #[Route('/lastlivres', name: 'app_livre_last', methods: ['GET'])]
    public function lastlivres(LivreRepository $livreRepository): Response
    {
        $livres=$livreRepository->lastlivres();
        $data = [];
        foreach ($livres as $livre) {

         
            $data[] = [
                'idLivre' => $livre->getIdlivre(),
                'idcategorie' => $livre->getIdCategorie()->getNom(),
                'idAuteur' => $livre->getIdAuteur()->getNom()." ".$livre->getIdAuteur()->getPrenom(),
                'titre' => $livre->getTitre(),
                'datePub' => $livre->getDatePub()? $livre->getDatePub()->format('Y-m-d') : null,
                'langue' => $livre->getLangue(),
                'isbn' => $livre->getisbn(),
                'nbPages' => $livre->getNbPages(),
                'resume' => $livre->getresume(),
                'prix' => $livre->getprix(),
              //  'image' => $livres->getImage(),

            ];
        }
       
        return $this->json($data); 
    }  
    

}
