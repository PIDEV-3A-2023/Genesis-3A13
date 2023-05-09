<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{

    #[Route('/register_rest', name: 'app_register_rest')]
    public function register_rest(Request $request, UserPasswordEncoderInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        // Parse JSON request body
        $data = json_decode($request->getContent(), true);

        // Create a new user entity and form instance
        $user = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $user);

        // Submit the form with JSON data
        $form->submit($data);

        // Validate the form data
        if (!$form->isSubmitted() || !$form->isValid()) {
            // Return validation errors as JSON response
            return new JsonResponse(['errors' => (string) $form->getErrors(true, true)], 400);
        }

        // Encode the password
        $user->setMotDePasse($userPasswordHasher->encodePassword($user, $form->get('plainPassword')->getData()));

        // Persist the new user entity
        $entityManager->persist($user);
        $entityManager->flush();

        // Return success response
        return new JsonResponse(['message' => 'User registered successfully.'], 201);
    }
    
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordEncoderInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setMotDePasse(
                $userPasswordHasher->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            if ($user->getRole() == 'Administrateur') {
                $user->setRoles("ROLE_ADMIN");
            } elseif ($user->getRole() == 'Client') {
                $user->setRoles("ROLE_USER");
            } elseif ($user->getRole() == 'Auteur') {
                $user->setRoles("ROLE_USER");
            } 

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
