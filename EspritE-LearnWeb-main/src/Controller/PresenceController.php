<?php

namespace App\Controller;

use App\Entity\Presence;
use App\Form\PresenceType;
use App\Repository\PresenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/presence')]
class PresenceController extends AbstractController
{
    #[Route('/', name: 'app_presence_index', methods: ['GET'])]
    public function index(PresenceRepository $presenceRepository): Response
    {
        return $this->render('presence/index.html.twig', [
            'presences' => $presenceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_presence_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $presence = new Presence();
        $form = $this->createForm(PresenceType::class, $presence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($presence);
            $entityManager->flush();

            return $this->redirectToRoute('app_presence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('presence/new.html.twig', [
            'presence' => $presence,
            'form' => $form,
        ]);
    }

    #[Route('/{idpresence}', name: 'app_presence_show', methods: ['GET'])]
    public function show(Presence $presence): Response
    {
        return $this->render('presence/show.html.twig', [
            'presence' => $presence,
        ]);
    }

    #[Route('/{idpresence}/edit', name: 'app_presence_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Presence $presence, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PresenceType::class, $presence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_presence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('presence/edit.html.twig', [
            'presence' => $presence,
            'form' => $form,
        ]);
    }

    #[Route('/{idpresence}', name: 'app_presence_delete', methods: ['POST'])]
    public function delete(Request $request, Presence $presence, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$presence->getIdpresence(), $request->request->get('_token'))) {
            $entityManager->remove($presence);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_presence_index', [], Response::HTTP_SEE_OTHER);
    }
}
