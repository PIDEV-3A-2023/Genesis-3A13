<?php

namespace App\Security;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
class AppAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';
    private $passwordEncoder;
    private $userRepository;
    private $tokenStorage;
    private $entityManager;

    

    public function __construct(private UrlGeneratorInterface $urlGenerator,UserPasswordEncoderInterface $passwordEncoder, UtilisateurRepository $userRepository,TokenStorageInterface $tokenStorage, EntityManagerInterface $entityManager)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;

    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->get('email');
        $password = $request->get('password');
    
        // Get the user from your User repository or database
        $user = $this->userRepository->findOneBy(['email' => $email]);
    
        if (!$user) {
            throw new CustomUserMessageAuthenticationException('email ou mot de passe invalide');
        }
    
        // Verify that the provided password matches with the user's encoded password
        $isPasswordValid = $this->passwordEncoder->isPasswordValid($user, $password);
    
        if (!$isPasswordValid) {
            throw new CustomUserMessageAuthenticationException('email ou mot de passe invalide');
        }
    
        return new Passport(
            new UserBadge($email, function () use ($user) {
                return $user;
            }),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }
    



    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $user = $this->tokenStorage->getToken()->getUser();

  

        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }
        $email = $request->get('email');

       // Get the user from your User repository or database
       $user = $this->userRepository->findOneBy(['email' => $email]);
       $role=$user->getRole();

       $lastcnx=$user->setLastConnection(new \DateTime());
        // Save the changes to the database
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        if ($role=='Auteur') {
            // Redirect to the admin dashboard
            return new RedirectResponse($this->urlGenerator->generate('index'));
        } elseif ($role=='Client') {
            // Redirect to the user dashboard
            return new RedirectResponse($this->urlGenerator->generate('index'));
        }
        elseif ($role=='Administrateur') {
            // Redirect to the user dashboard
            return new RedirectResponse($this->urlGenerator->generate('app_dashboard'));
        }else {
            error_log('Unknown role: ' . $role);  // or var_dump($role);
            return new RedirectResponse($this->urlGenerator->generate('index'));
        }

        // For example:
        //throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
