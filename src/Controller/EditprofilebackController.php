<?php

namespace App\Controller;
use App\Entity\Utilisateur;
use App\Form\Modifyprofiletype;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EditprofilebackController extends AbstractController
{
    #[Route('/editprofileback', name: 'app_editprofileback')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('editprofileback/index.html.twig', [
            'controller_name' => 'EditprofilebackController',
        ]);
    }

    #[Route('/{idUtilisateur}/profileeditback', name: 'app_profile_editback', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(Modifyprofiletype::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur mise à jour avec succés!');
            return $this->redirectToRoute('app_dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('editprofileback/profileeditback.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }
}
