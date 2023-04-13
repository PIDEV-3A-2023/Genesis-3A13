<?php

namespace App\Controller;

use App\Entity\Messagerie;
use App\Form\MessagerieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/messagerie')]
class MessagerieController extends AbstractController
{
    #[Route('/', name: 'app_messagerie_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $messageries = $entityManager
            ->getRepository(Messagerie::class)
            ->findAll();

        return $this->render('messagerie/index.html.twig', [
            'messageries' => $messageries,
        ]);
    }

    #[Route('/new', name: 'app_messagerie_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $messagerie = new Messagerie();
    $messagerie->setDateHeure(new \DateTime());
    $form = $this->createForm(MessagerieType::class, $messagerie);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        
        $entityManager->persist($messagerie);
        $entityManager->flush();

        return $this->redirectToRoute('app_messagerie_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('messagerie/new.html.twig', [
        'messagerie' => $messagerie,
        'form' => $form,
    ]);
}

    #[Route('/{idMessagerie}', name: 'app_messagerie_show', methods: ['GET'])]
    public function show(Messagerie $messagerie): Response
    {
        return $this->render('messagerie/show.html.twig', [
            'messagerie' => $messagerie,
        ]);
    }

    #[Route('/{idMessagerie}/edit', name: 'app_messagerie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Messagerie $messagerie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MessagerieType::class, $messagerie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_messagerie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('messagerie/edit.html.twig', [
            'messagerie' => $messagerie,
            'form' => $form,
        ]);
    }

    #[Route('/{idMessagerie}', name: 'app_messagerie_delete', methods: ['POST'])]
    public function delete(Request $request, Messagerie $messagerie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$messagerie->getIdMessagerie(), $request->request->get('_token'))) {
            $entityManager->remove($messagerie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_messagerie_index', [], Response::HTTP_SEE_OTHER);
    }
}
