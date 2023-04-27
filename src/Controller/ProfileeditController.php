<?php

namespace App\Controller;
use App\Entity\Utilisateur;
use App\Form\Modifyprofiletype;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ProfileeditController extends AbstractController
{
    #[Route('/profileedit', name: 'app_profileedit')]
    public function index(): Response
    {
        return $this->render('profileedit/index.html.twig', [
            'controller_name' => 'ProfileeditController',
        ]);
    }

    #[Route('/{idUtilisateur}/profileedit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Modifyprofiletype::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur mise à jour avec succés!');
            return $this->redirectToRoute('app_profileedit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profileedit/profileedit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }
}
