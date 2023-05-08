<?php

namespace App\Controller;

use App\Entity\Fidelite;
use App\Entity\Utilisateur;

use App\Form\FideliteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FideliteRepository;
use App\Repository\CommandeRepository;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Options\ChartOptions;
use Symfony\Component\Serializer\Normalizer\DataUriNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\File\File;
#[Route('/fidelite')]
class FideliteController extends AbstractController
{
    #[Route('/', name: 'app_fidelite_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager,FideliteRepository $repo , CommandeRepository $repcom): Response
    {
        $fidelites = $entityManager
            ->getRepository(Fidelite::class)
            ->findAll();


        /*$usernonfidelite=$repo->getUtilisateursSansFidelite();
        foreach ($usernonfidelite as $usernon){
            $totalAchat = $repo->calculateTotalAchatByIdClient($usernon);
            $totalAchatInt = intval($totalAchat);

            $type=$this->tyyype($totalAchatInt);
            $fideliteabc = new Fidelite();
            $fideliteabc->setTotalachat($totalAchatInt);
            $fideliteabc->settype($type);
            $fideliteabc->setIdClient($usernon);
            $entityManager->persist($fidelite);
            $entityManager->flush();


        }

*/
            $commandes=$repcom->findAll();
            foreach ($commandes as $com) {
                $fid=$repo->findFideliteByIdClient($com->getIdClient()); 
                if ( $fid ==null){
                    $fid=new Fidelite();
                    $totalAchat = $repo->calculateTotalAchatByIdClient($com->getIdClient());
                     $totalAchatInt = intval($totalAchat);

                     $fid->setTotalachat($totalAchatInt);
                     $fid->setIdClient($com->getIdClient());
                     $fid->setType($this->tyyype($totalAchatInt));

                     $entityManager->persist($fid);

                     $entityManager->flush();


                }
            }


            foreach ($fidelites as $fideliteee) {

            //$fidelite = $repo->findFideliteByIdClient($fideliteee->getIdClient());
            //if (!$fidelite) {
              //  throw $this->createNotFoundException('Client non trouvé pour l\'ID ' . $fideliteee->getIdClient());
            //}
            $totalAchat = $repo->calculateTotalAchatByIdClient($fideliteee->getIdClient());
            $totalAchatInt = intval($totalAchat);

                $fideliteee->setTotalachat($totalAchatInt);
                $entityManager->persist($fid);


            $entityManager->flush();

            $fideliteee->setType($this->tyyype($totalAchatInt));
        }



        return $this->render('fidelite/index.html.twig', [
            'fidelites' => $fidelites,
            
        ]);
    }
    #[Route('/stat', name: 'app_stat')]
    public function displayStats(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $stats = $entityManager->createQueryBuilder()
        ->select('f.type as nom, COUNT(f.idFidelite) as type')

         ->from(Fidelite::class, 'f')
        ->groupBy('f.type')
        ->getQuery()
        ->getResult();
        return $this->render('fidelite/statss.html.twig', [
            'stats' => $stats,
        ]);
    }
    #[Route('/new', name: 'app_fidelite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $fidelite = new Fidelite();
        $form = $this->createForm(FideliteType::class, $fidelite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($fidelite);
            $entityManager->flush();

            return $this->redirectToRoute('app_fidelite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fidelite/new.html.twig', [
            'fidelite' => $fidelite,
            'form' => $form,
        ]);
    }

    #[Route('/{idFidelite}', name: 'app_fidelite_show', methods: ['GET'])]
    public function show(Fidelite $fidelite): Response
    {
        return $this->render('fidelite/show.html.twig', [
            'fidelite' => $fidelite,
        ]);
    }

    #[Route('/{idFidelite}/edit', name: 'app_fidelite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Fidelite $fidelite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FideliteType::class, $fidelite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_fidelite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fidelite/edit.html.twig', [
            'fidelite' => $fidelite,
            'form' => $form,
        ]);
    }

    #[Route('/{idFidelite}', name: 'app_fidelite_delete', methods: ['POST'])]
    public function delete(Request $request, Fidelite $fidelite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fidelite->getIdFidelite(), $request->request->get('_token'))) {
            $entityManager->remove($fidelite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fidelite_index', [], Response::HTTP_SEE_OTHER);
    }

    public function tyyype(int $total): string
    {
        if ($total < 1000) {
            return 'bronze';
        } elseif ($total >= 1000 && $total < 2000) {
            return 'sliver';
        } elseif ($total >= 2000) {
            return 'gold';
        }
    }




    public function stats(Request $request, FideliteRepository $repo): Response
    {
        // Get the number of clients for each type of fidelity
        $bronzeClients = $repo->countClientsByFidelity('bronze');
        $silverClients = $repo->countClientsByFidelity('silver');
        $goldClients = $repo->countClientsByFidelity('gold');
        $chart = new PieChart();
        $chart->getData()->setArrayToDataTable([
            ['Type de fidélité', 'Nombre de clients'],
            ['Bronze', $bronzeClients],
            ['Silver', $silverClients],
            ['Gold', $goldClients]
        ]);
        var_dump($chart);
        die();
        $chart->getOptions()->setTitle('Répartition des clients selon le type de fidélité');
        $chart->getOptions()->getTitleTextStyle()->setColor('#000000');
        $chart->getOptions()->getTitleTextStyle()->setBold(true);
        $chart->getOptions()->getTitleTextStyle()->setItalic(true);
        $chart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $chart->getOptions()->getTitleTextStyle()->setFontSize(20);
        $chart->getOptions()->getLegend()->getTextStyle()->setColor('#000000');
        $chart->getOptions()->setHeight(300);
        $chart->getOptions()->setWidth(600);
        $chart->getOptions()->setBackgroundColor('#FFFFFF');
        $chart->getOptions()->setColors(['#FFA07A', '#90EE90', '#FFD700']);
    
        return $this->render('fidelite/index.html.twig', [
            'chart' => $chart,
            'bronzeClients' => $bronzeClients,
            'silverClients' => $silverClients,
            'goldClients' => $goldClients,
            'chart' => $chart
        ]);
    }
    
    #[Route('/get', name: 'app_fidelite_front_rest')]
    public function index_rest(FideliteRepository $repo, BlobExtension $blobExtension): Response
    {
        $fidelites = $repo->findAll();
        $data = [];
        foreach ($fidelites as $fidelite) {

         
            $data[] = [
                'idFidelite' => $fidelite->getIdFidelite(),
                'totalAchat' => $fidelite->totalAchat(),
                'type' => $fidelite->type(),
                'idClient' => $fidelite->idClient(),
             
            ];
        }
        return $this->json($data, 200, ['Content-Type' => 'application/json']);

    }
}
