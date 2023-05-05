<?php

namespace App\Controller;

use App\Entity\Messagerie;
use App\Form\MessagerieType;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\MessagerieRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;



#[Route('/messagerie')]
class MessagerieController extends AbstractController
{
    public function myAction(Request $request, ValidatorInterface $validator)
    {
        // Create a new Messagerie entity and set its properties
        $message = new Messagerie();
        $message->setMessage($request->get('message'));

        // Validate the entity
        $errors = $validator->validate($message);

        // If there are validation errors, display them
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                echo $error->getMessage();
            }
        }
        
        // ...
    }
    #[Route('/', name: 'app_messagerie_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager,MessagerieRepository $messagerieRepository, Security $security): Response
    {
     
        $messageries = $messagerieRepository

            ->findByUser($security->getUser());
           
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
    
            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
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

/**
 * @Route("/messagerie/rate", name="app_messagerie_rate")
 * @IsGranted("ROLE_USER")
 */
public function rateMessage(Request $request, EntityManagerInterface $entityManager)
{
    $messageId = $request->request->get('messageId');
    $rating = $request->request->get('rating');

    $message = $entityManager->getRepository(Messagerie::class)->find($messageId);

    if (!$message) {
        throw $this->createNotFoundException('The message does not exist');
    }

    $message->setRating($rating);

    $entityManager->flush();

    return $this->redirectToRoute('app_messagerie_index');
}

}