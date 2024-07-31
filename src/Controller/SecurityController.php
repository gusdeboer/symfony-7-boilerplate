<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Webmozart\Assert\Assert;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser() instanceof UserInterface) {
            return $this->redirectToRoute('app_home');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/signup', name: 'app_signup', methods: ['GET', 'POST'])]
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordEncoder): Response
    {
        if ($this->getUser() instanceof \Symfony\Component\Security\Core\User\UserInterface) {
            return $this->redirectToRoute('app_home');
        }

        $user = new User();

        $user->setRoles(['ROLE_USER']);
        $user->setOauth('basic');

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $pseudo = $form->get('username')->getData();
            $email = $form->get('email')->getData();

            $existingUser = $manager->getRepository(User::class)->findOneBy(['username' => $pseudo]);

            if (null !== $existingUser) {
                $this->addFlash(
                    'error',
                    'Username already exists.'
                );

                return $this->render('security/signup.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            $existingUser = $manager->getRepository(User::class)->findOneBy(['email' => $email]);

            if (null !== $existingUser) {
                $this->addFlash(
                    'error',
                    'Email already exists.'
                );

                return $this->render('security/signup.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            if ($form->isValid()) {
                $user = $form->getData();

                Assert::isInstanceOf($user, User::class);

                $password = $form->get('password')->getData();

                Assert::string($password);

                // Hash the password
                $user->setPassword(
                    $passwordEncoder->hashPassword(
                        $user,
                        $password
                    )
                );

                $this->addFlash(
                    'success',
                    'Your account has been created. You can now log in.'
                );

                $manager->persist($user);
                $manager->flush();

                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('security/signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/login/google', name: 'google_connect', methods: ['GET'])]
    public function googleConnect(ClientRegistry $clientRegistry): Response
    {
        $client = $clientRegistry->getClient('google');

        return $client->redirect(['email', 'profile'], []);
    }

    #[Route('/login/google/check', name: 'google_connect_check', methods: ['GET'])]
    public function googleConnectCheck(ClientRegistry $clientRegistry, EntityManagerInterface $em, TokenStorageInterface $tokenStorage, SessionInterface $session): Response
    {
        $client = $clientRegistry->getClient('google');

        try {
            $user = $client->fetchUser();

            $userData = $user->toArray();

            $fullName = $userData['name'];
            $nameParts = explode(' ', $fullName);

            $firstName = array_shift($nameParts);
            $lastName = implode(' ', $nameParts);

            $userRepository = $em->getRepository(User::class);
            $existingUser = $userRepository->findOneBy(['email' => $userData['email']]);

            if (null !== $existingUser) {
                $existingUser->setFirstname($firstName);
                $existingUser->setLastname($lastName);
                $newUser = $existingUser;
            } else {
                $newUser = new User();
                $newUser->setUsername($userData['given_name']);
                $newUser->setEmail($userData['email']);
                $newUser->setOauth('google');
                $newUser->setRoles(['ROLE_USER']);
                $newUser->setProfilePicture($userData['picture']);
                $newUser->setPassword('google');
                $newUser->setFirstname($firstName);
                $newUser->setLastname($lastName);
            }

            $em->persist($newUser);
            $em->flush();

            $token = new UsernamePasswordToken($newUser, 'main', $newUser->getRoles());

            $tokenStorage->setToken($token);
            $session->set('_security_main', serialize($token));

            return $this->redirectToRoute('app_home');
        } catch (IdentityProviderException $identityProviderException) {
            var_dump($identityProviderException->getMessage());
            exit;
        }
    }

    #[Route('/login/azure', name: 'azure_connect', methods: ['GET'])]
    public function azureConnect(ClientRegistry $clientRegistry): Response
    {
        return $clientRegistry
            ->getClient('azure')
            ->redirect([], []);
    }

    #[Route('/login/azure/check', name: 'azure_connect_check', methods: ['GET'])]
    public function azureConnectCheck(ClientRegistry $clientRegistry, EntityManagerInterface $em, TokenStorageInterface $tokenStorage, SessionInterface $session): Response
    {
        $client = $clientRegistry->getClient('azure');

        try {
            $user = $client->fetchUser();

            $userData = $user->toArray();

            $fullName = $userData['name'];
            $nameParts = explode(' ', $fullName);

            $firstName = array_shift($nameParts);
            $lastName = implode(' ', $nameParts);

            $userRepository = $em->getRepository(User::class);
            $existingUser = $userRepository->findOneBy(['email' => $userData['email']]);

            if (null !== $existingUser) {
                $existingUser->setFirstname($firstName);
                $existingUser->setLastname($lastName);
                $newUser = $existingUser;
            } else {
                $newUser = new User();
                $newUser->setUsername($userData['given_name']);
                $newUser->setEmail($userData['email']);
                $newUser->setOauth('azure');
                $newUser->setRoles(['ROLE_USER']);
                $newUser->setProfilePicture($userData['picture'] ?? null);
                $newUser->setPassword('azure');
                $newUser->setFirstname($firstName);
                $newUser->setLastname($lastName);
            }

            $em->persist($newUser);
            $em->flush();

            $token = new UsernamePasswordToken($newUser, 'main', $newUser->getRoles());

            $tokenStorage->setToken($token);
            $session->set('_security_main', serialize($token));

            return $this->redirectToRoute('app_home');
        } catch (IdentityProviderException $identityProviderException) {
            var_dump($identityProviderException->getMessage());
            exit;
        }
    }
}
