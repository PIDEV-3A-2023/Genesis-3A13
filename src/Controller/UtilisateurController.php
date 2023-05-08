<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Annotation\Groups;

use Symfony\Component\Serializer\SerializerInterface;
#[Route('/utilisateur')]
class UtilisateurController extends AbstractController
{
    #[Route('/', name: 'app_utilisateur_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $utilisateurs = $entityManager
            ->getRepository(Utilisateur::class)
            ->findAll();

        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }

    #[Route('/new', name: 'app_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($utilisateur);
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur ajouté avec succés!');
            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/newrest', name: 'app_utilisateur_new_rest', methods: ['GET', 'POST'])]
    public function newrest(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($utilisateur);
            $entityManager->flush();
            $data = [
                'success' => true,
                'message' => 'Utilisateur ajouté avec succés!',
                'utilisateur' => $utilisateur,
            ];
            return new JsonResponse($data, JsonResponse::HTTP_CREATED);
        }
    
        $data = [
            'success' => false,
            'message' => 'Formulaire invalide',
            'errors' => $form->getErrors(),
        ];
        return new JsonResponse($data, JsonResponse::HTTP_BAD_REQUEST);
    }


    #[Route('/{idUtilisateur}', name: 'app_utilisateur_show', methods: ['GET'])]
    public function show(Utilisateur $utilisateur): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/{idUtilisateur}/edit', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur mise à jour avec succés!');
            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{idUtilisateur}', name: 'app_utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getIdUtilisateur(), $request->request->get('_token'))) {
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Utilisateur supprimée avec succés!');
        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/registerrest', name: 'app_register_rest', methods: ['POST','GET'])]
    public function register_rest(Request $request, SerializerInterface $serializer, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $em = $this->getDoctrine()->getManager();
        $nom = $request->get('nom');
        $prenom = $request->get('prenom');
        $email = $request->get('email');
        $motDePasse = $request->get('motDePasse');
        $numTelephone = $request->get('numTelephone');
        $role = $request->get('role');
    
        $user = new Utilisateur();
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setEmail($email);
        $user->setMotDePasse(
            $userPasswordHasher->hashPassword(
                $user,
                $motDePasse
            )
        );
        $user->setNumTelephone(intval($numTelephone));
        $user->setRole($role);
        $participantsArray = json_decode('$user', true); // On utilise json_decode pour convertir la liste de participants en tableau associatif
        $em->persist($participantsArray);
        $em->flush();
        
        try {
            $json = $serializer->serialize($user, 'json', ['groups' => 'users']);
    
            return new Response($json, 200, [
                'Content-Type' => 'application/json'
            ]);
        } catch (\Exception $ex) {
            return new Response('Account was not created! ' . $ex->getMessage());
        }
    }

    #[Route('/registerrestt', name: 'app_register_restt', methods: ['POST','GET'])]
    public function register_restt(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager)
    {

        $nom= $request->query->get("nom");
        $prenom= $request->query->get("prenom");
        $email= $request->query->get("email");
        $motDePasse= $request->query->get("motDePasse");
        $numTelephone= $request->query->get("numTelephone");
        $role= $request->query->get("role");


        $user=new Utilisateur();
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setEmail($email);
        $user->setMotDePasse(
            $userPasswordHasher->hashPassword(
                $user,
                $motDePasse
            )
            );
        $user->setNumTelephone(intval($numTelephone));
        $user->setRole($role);
        try{
            $entityManager->persist($user);
            $entityManager->flush();

            return new JsonResponse("Account is crated succesefully!",200);

        }catch(\Exception $ex){
            return new Response("Account was not created!".$ex->getMessage());
        }



    }
}
