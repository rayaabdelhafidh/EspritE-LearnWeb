<?php

namespace App\Controller;

use App\Entity\Plandetude;
use App\Form\PlandetudeType;
use App\Repository\PlandetudeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/plandetude')]
class PlandetudeController extends AbstractController
{
    #[Route('/plan', name: 'app_plandetude_index', methods: ['GET'])]
    public function index(PlandetudeRepository $plandetudeRepository): Response
    {
        return $this->render('plandetude/index.html.twig', [
            'plandetudes' => $plandetudeRepository->findAll(),
        ]);
    }
    #[Route('/planEtud', name: 'app_plandetude_indexEtud', methods: ['GET'])]
    public function indexEtud(PlandetudeRepository $plandetudeRepository): Response
    {
        return $this->render('plandetude/indexEtud.html.twig', [
            'plandetudes' => $plandetudeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_plandetude_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $plandetude = new Plandetude();
        $form = $this->createForm(PlandetudeType::class, $plandetude);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($plandetude);
            $entityManager->flush();

            return $this->redirectToRoute('app_plandetude_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('plandetude/new.html.twig', [
            'plandetude' => $plandetude,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_plandetude_show', methods: ['GET'])]
    public function show(Plandetude $plandetude): Response
    {
        return $this->render('plandetude/show.html.twig', [
            'plandetude' => $plandetude,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_plandetude_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Plandetude $plandetude, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlandetudeType::class, $plandetude);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_plandetude_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('plandetude/edit.html.twig', [
            'plandetude' => $plandetude,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_plandetude_delete', methods: ['POST'])]
    public function delete(Request $request, Plandetude $plandetude, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$plandetude->getId(), $request->request->get('_token'))) {
            $entityManager->remove($plandetude);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_plandetude_index', [], Response::HTTP_SEE_OTHER);
    }
}
