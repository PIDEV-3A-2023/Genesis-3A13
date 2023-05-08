<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ApiuserController extends AbstractController
{
    #[Route('/apiuser', name: 'app_apiuser')]
    public function index(): Response
    {
        return $this->render('apiuser/index.html.twig', [
            'controller_name' => 'ApiuserController',
        ]);
    }

    #[Route('/signup', name: 'signUpMobile', methods: ['GET','POST'])]
    public function signUpMobile(UserPasswordEncoderInterface $userPasswordEncoder
    ,NormalizerInterface $Normalizer,
    Request $request,EntityManagerInterface $entityManager): Response
    {
        $em = $this->getDoctrine()->getManager();
 
        $user = new Utilisateur();
        $user->setEmail($request->get('email'));
       // $user->setPassword($request->get('password'));
        $user->setMotDePasse(
            $userPasswordEncoder->encodePassword(
                    $user,
                    $request->get('motDePasse')
                )
            );
        $user->setNom($request->get('nom'));
        $user->setPrenom($request->get('prenom'));
        $user->setNumTelephone(intval($request->get('numTelephone')));
        $user->setRole($request->get('role'));
        $em->persist($user);
        
        $em->flush();
            $jsonContent = $Normalizer->normalize($user, 'json',['groups'=>'post:read']);
            return new Response(json_encode($jsonContent));

    }

    #[Route(path: '/loginrests', name: 'loginrest', methods: ['GET','POST'])]
    public function siginMobile(UserPasswordEncoderInterface $userPasswordEncoder,NormalizerInterface $Normalizer,Request $request,EntityManagerInterface $entityManager): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $email=$request->get('email');
        $user = $entityManager->getRepository(Utilisateur::class)->findBy(['email' => $request->get('email')]);
        if ($user){
            
 //$password=$request->get('password');
 $password =   
 $userPasswordEncoder->isPasswordValid(
         $user[0],
         $request->get('motDePasse')
         
     
 );
 if($password){

    $jsonContent = $Normalizer->normalize($user, 'json',['groups'=>'post:read']);
    return new Response(json_encode($jsonContent));
 }else{
    return new Response("failed");
 }
        }
       
        //$user = $entityManager->getRepository(Users::class)->findBy(['email'=>$email,'password'=>$password]);
      else{
            return new Response("failed");

        };
       

    }


    #[Route(path: '/updateprofile/{idUtilisateur}', name: 'update', methods: ['GET','POST'])]
    public function updateProfileMobile($idUtilisateur,NormalizerInterface $Normalizer,Request $request,EntityManagerInterface $entityManager): Response
    {
        $em = $this->getDoctrine()->getManager();
       
$user = $em->getRepository(Utilisateur::class)->find($idUtilisateur);
        $user->setNom($request->get('nom'));
        $user->setEmail($request->get('email'));
        $user->setPrenom($request->get('prenom'));
        $user->setNumTelephone(intval($request->get('numTelephone')));
        $user->setRole($request->get('role'));
   
        $em->persist($user);
        $em->flush();
            $jsonContent = $Normalizer->normalize($user, 'json',['groups'=>'post:read']);
            return new Response(json_encode($jsonContent));

    }
    
}
