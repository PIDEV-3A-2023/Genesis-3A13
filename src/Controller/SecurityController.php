<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\UsersRepository;
use App\Repository\UtilisateurRepository;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;    
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Twilio\Serialize;

class SecurityController extends AbstractController
{

    #[Route(path: '/login_rest', name: 'app_login_rest')]
    public function login_rest(Request $request,UserPasswordEncoderInterface $userPasswordEncoder,NormalizerInterface $Normalizer,EntityManagerInterface $entityManager)
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }
        $email = $request->query->get('email');
        $password = $request->query->get('motDePasse');
        // get the login error if there is one
        $em= $this->getDoctrine()->getManager();
        $user= $em->getRepository(Utilisateur::class)->findOneBy(['email'=>$email]);
        if($user){

            if(password_verify($password,$user->getMotDePasse())){
                $serializer= new Serializer([new ObjectNormalizer()]);
                $formatted=$serializer->Normalize($user);
                return new JsonResponse($formatted);
            }
            else{
                return new Response("password invalid");
            }
        }else{
            return new Response("User invalid");

        }
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/oubli-pass', name: 'forgotten_password')]
    public function forgottenPassword(Request $request,
                                      UtilisateurRepository $usersRepository,
                                      TokenGeneratorInterface $tokenGenerator,
                                      EntityManagerInterface $entityManager,
                                      SendMailService $mail

    ): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form -> handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            //on va chercher l'utilisateur par son email
            $user = $usersRepository->findOneByEmail($form->get('email')->getData());
            if($user){
                $token = $tokenGenerator->generateToken();
                $user->setResetToken($token);
                $entityManager->persist($user);
                $entityManager->flush();

                $url= $this->generateUrl('reset_pass',['token'=> $token],UrlGeneratorInterface::ABSOLUTE_URL);

                $context = compact('url','user');

                $mail->send(
                  
                  $user->getEmail(),
                  'Réinitialisation de mot de passe',
                  'password_reset',
                  $context
                );

                return $this->redirectToRoute('app_login');

            }

        }
       return $this->render('security/reset_password_request.html.twig', [
           'requestPassForm' => $form ->createView()
       ]);

    }
    #[Route('/oubli-pass/{token}', name:'reset_pass')]
    public function resetPass(
        string $token,
        Request $request,
        UtilisateurRepository $usersRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        // On vérifie si on a ce token dans la base
        $user = $usersRepository->findOneByResetToken($token);

        if($user){
            $form = $this->createForm(ResetPasswordFormType::class);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                // On efface le token
                $user->setResetToken('');
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Mot de passe changé avec succès');
                return $this->redirectToRoute('app_login');
            }

            return $this->render('security/reset_password.html.twig', [
                'passForm' => $form->createView()
            ]);
        }
        $this->addFlash('danger', 'Jeton invalide');
    }
}
