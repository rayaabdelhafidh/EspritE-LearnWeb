<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\UserAuthenticator;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;



use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    #[Route('/registerChoices', name: 'app_registerChoices', methods: ['GET'])]
    public function registerChoices(): Response
    {
        return $this->render('registration/registerChoices.html.twig');
    }

    #[Route('/registerAdmin', name: 'app_registerAdmin')]
    public function registerAdmin(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $role = "ROLE_ADMIN";
        $user = new User();
        $filesystem = new Filesystem();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $user->setRoles([$role]);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            return $this->redirectToRoute('app_login');
        }
        return $this->render('registration/registerAdmin.html.twig', [
            'registrationForm' => $form->createView()
        ]);

    }


    #[Route('/registerEtudiant', name: 'app_registerEtudiant')]
    public function registerEtudiant(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $role = "ROLE_ETUDIANT";
        $user = new User();
        $filesystem = new Filesystem();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $user->setRoles([$role]);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
           
            // do anything else you need here, like send an email
            return $this->redirectToRoute('app_login');
        }
        return $this->render('registration/registerEtudiant.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    #[Route('/registerEnseignant', name: 'app_registerEnseignant')]
    public function registerEnseignant(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $role = "ROLE_ENSEIGNANT";
        $filesystem = new Filesystem();
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() ) {
            $user->setRoles([$role]);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
        
            // do anything else you need here, like send an email
            return $this->redirectToRoute('app_login');


        }

        return $this->render('registration/registerEnseignant.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

}