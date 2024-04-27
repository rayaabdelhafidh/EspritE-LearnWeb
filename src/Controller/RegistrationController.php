<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Twilio\Rest\Client;
use Psr\Log\LoggerInterface;




class RegistrationController extends AbstractController
{
    private $twilioClient;
    private $logger;

    public function __construct(Client $twilioClient, LoggerInterface $logger)
    {
        $this->logger = $logger;

        $this->twilioClient = $twilioClient;
    }
    #[Route('/register', name: 'app_registerChoices', methods: ['GET'])]
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
    public function registerEtudiant(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager, Client $twilioClient , LoggerInterface $logger): Response
    {
        $role = "ROLE_ETUDIANT";
        $user = new User();
        $filesystem = new Filesystem();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recaptchaResponse = $request->request->get('g-recaptcha-response');
            $isRecaptchaValid = $this->isRecaptchaValid($recaptchaResponse);

            if (!$isRecaptchaValid) {
                $this->addFlash('error', 'reCAPTCHA validation failed. Please try again.');
                return $this->redirectToRoute('app_registerEtudiant');
            }

            $user->setRoles([$role]);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
           
            // Retrieve the user's telephone number from the database
            
            $email = $form->get('email')->getData();



           // Get telephone number from the form
            $tel = '+216' . $form->get('tel')->getData();

            $user->setTel($tel);   
                    // Send SMS via Twilio after registration
            try {
                        // Send SMS to the registered user
                $toNumber = $user->getTel();
                $fromNumber = '+19896743241';
            
                $message = $twilioClient->messages->create(
                    $toNumber,
                    [
                        'from' => $fromNumber,
                        'body' => 'You have been successfully registered. Your email is: ' . $email
                    ]);
                        
                    $logger->info('SMS sent with ID: ' . $message->sid);
                } catch (\Exception $e) {
                    $logger->error('Failed to send SMS: ' . $e->getMessage());
                }
        
            // do anything else you need here, like send an email
            return $this->redirectToRoute('app_login');
        }
        return $this->render('registration/registerEtudiant.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    #[Route('/registerEnseignant', name: 'app_registerEnseignant')]
    public function registerEnseignant(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager, Client $twilioClient , LoggerInterface $logger): Response
    {
        $role = "ROLE_ENSEIGNANT";
        $filesystem = new Filesystem();
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            $recaptchaResponse = $request->request->get('g-recaptcha-response');
            $isRecaptchaValid = $this->isRecaptchaValid($recaptchaResponse);

            if (!$isRecaptchaValid) {
                $this->addFlash('error', 'reCAPTCHA validation failed. Please try again.');
                return $this->redirectToRoute('app_registerEtudiant');
            }

            $user->setRoles([$role]);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
            $email = $form->get('email')->getData();



            // Get telephone number from the form
             $tel = '+216' . $form->get('tel')->getData();
 
             $user->setTel($tel);   
                     // Send SMS via Twilio after registration
             try {
                         // Send SMS to the registered user
                 $toNumber = $user->getTel();
                 $fromNumber = '+19896743241';
             
                 $message = $twilioClient->messages->create(
                     $toNumber,
                     [
                         'from' => $fromNumber,
                         'body' => 'You have been successfully registered. Your email is: ' . $email
                     ]);
                         
                     $logger->info('SMS sent with ID: ' . $message->sid);
                 } catch (\Exception $e) {
                     $logger->error('Failed to send SMS: ' . $e->getMessage());
                 }
            // do anything else you need here, like send an email
            return $this->redirectToRoute('app_login');


        }

        return $this->render('registration/registerEnseignant.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    private function isRecaptchaValid($recaptchaResponse)
    {
        $secretKey = '6LdgqIgpAAAAAIL7zt1gZh87stU7vGsgR3Yl7h7X';
        $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
        $postData = http_build_query([
            'secret' => $secretKey,
            'response' => $recaptchaResponse
        ]);

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postData
            ]
        ]);

        $response = file_get_contents($recaptchaUrl, false, $context);
        $result = json_decode($response);

        return $result->success;
    }

}