<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Fidelite;
use App\Repository\FideliteRepository;
use App\Repository\UtilisateurRepository;
use App\Entity\Utilisateur;
use Symfony\Component\Security\Core\Security;


use Doctrine\ORM\EntityManagerInterface;

#[Route('/fidelitee')]

class FideliteFrontController extends AbstractController
{
    #[Route('/ff', name: 'app_fidelite_front')]
    public function index(EntityManagerInterface $entityManager,FideliteRepository $repo,UtilisateurRepository $repouser, Security $security): Response
    {
        $Client = $security->getUser();
        $idComp = array('idUtilisateur' => $Client);
        $idClient = $repouser->findOneBy($idComp);
        $fidelite = $entityManager->getRepository(Fidelite::class)->findOneByIdClient($idClient->getIdUtilisateur());
        $user = $entityManager->getRepository(Utilisateur::class)->find($idClient->getIdUtilisateur());
        $totalAchat = $repo->calculateTotalAchatByIdClient($idClient->getIdUtilisateur());
        $totalAchatInt = intval($totalAchat);

            $fidelite->setTotalachat($totalAchatInt);
        $entityManager->flush();

        $fidelite->settype($this->tyyype($totalAchatInt));

        return $this->render('fidelite_front/index.html.twig', [
            'fidelite' => $fidelite,
            'user'=> $user,
            'controller_name' => 'FideliteFrontController',
        ]);
    }


 public function tyyype(int $total): string
    {
        if ($total < 1000) {
            return 'bronze';
        } elseif ($total >= 1000 && $total < 2000) {
            return 'silver';
        } elseif ($total >= 2000) {
            return 'gold';
        }
    }
     
    #[Route('/get/{idClient}', name: 'app_fidelite_show_rest', methods: ['GET'])]
    public function showOne(Fidelite $fidelite): Response
    {
        $data = [];
        $data[] = [
            
                'idFidelite' => $fidelite->getIdFidelite(),
                'totalAchat' => $fidelite->getTotalAchat(),
                'type' => $fidelite->getType(),
                'idClient' => $fidelite->getIdClient()->getIdUtilisateur(),           
            ];
        
            return $this->json($data, 200, ['Content-Type' => 'application/json']);

    }
}
