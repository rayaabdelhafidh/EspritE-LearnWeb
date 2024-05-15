<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(Request $request,UserRepository $userRepository): Response
    {
        $searchTerm = $request->query->get('search');

        if ($searchTerm) {
            $users = $userRepository->findByNom($searchTerm);
        } else {
            $users = $userRepository->findAll();
        }

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }
    #[Route('/sort', name: 'app_user_sort', methods: ['GET'])]
    public function sort(Request $request, UserRepository $userRepository): Response
    {
        $criteria = $request->query->get('criteria', 'nom');
        $direction = $request->query->get('direction', 'asc');
    
        $users = $userRepository->findBy([], [$criteria => $direction]);
    
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/users/etudiant', name: 'app_user_etudiant', methods: ['GET'])]
    public function listetudiants(Request $request,UserRepository $userRepository): Response
    {
        $etudiant = $userRepository->findByRole('ROLE_ETUDIANT');

        
        return $this->render('user/etudiant.html.twig', [
            'etudiants' => $etudiant,
        ]);
    }
    #[Route('/users/Enseignant', name: 'app_user_enseignant', methods: ['GET'])]
    public function listenseignant(UserRepository $userRepository): Response
    {
        $enseignants = $userRepository->findByRole('ROLE_ENSEIGNANT');

        return $this->render('user/enseignant.html.twig', [
            'enseignants' => $enseignants,
        ]);
    }
    #[Route('/{id}/block', name: 'app_user_block', methods: ['POST'])]
    public function blockUser(User $user, EntityManagerInterface $entityManager): RedirectResponse
    {
        // Toggle the blocked status
        $user->setBlocked(!$user->isBlocked());
        
        // Save the changes
        $entityManager->flush();
        
        // Redirect back to the user listing page
        return $this->redirectToRoute('app_user_index');
    }
   
}
