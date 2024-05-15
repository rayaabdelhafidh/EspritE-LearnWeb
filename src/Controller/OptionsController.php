<?php

namespace App\Controller;

use App\Entity\Options;
use App\Form\OptionsType;
use App\Repository\QuestionRepository;
use App\Repository\OptionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Question;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/options')]
class OptionsController extends AbstractController
{
   
    #[Route('/', name: 'app_options_index', methods: ['GET'])]
    public function index(Request $request, OptionsRepository $optionsRepository, PaginatorInterface $paginator): Response
    {
        $sort = $request->query->get('sort');
        if ($sort === 'asc') {
            $options = $optionsRepository->findBy([], ['optionContent' => 'ASC']);
        } elseif ($sort === 'desc') {
            $options = $optionsRepository->findBy([], ['optionContent' => 'DESC']);
        } else {
            $options = $optionsRepository->findAll();
        }
        $optionsPaginated = $paginator->paginate(
            $options, // Les résultats à paginer
            $request->query->getInt('page', 1), // Numéro de page à afficher
            3
        );

        return $this->render('options/index.html.twig', [
            'options' => $optionsPaginated,
        ]);
    }

    #[Route('/new', name: 'app_options_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {   $questionid = $request->query->getInt('questionid');
        $question = $this->getDoctrine()->getRepository(Question::class)->find($questionid);
        if (!$question) {
            throw $this->createNotFoundException('Question not found');
        }
        $option = new Options();
        $option->setQuestion($question);
        $form = $this->createForm(OptionsType::class, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($option);
            $entityManager->flush();

            return $this->redirectToRoute('app_question_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('options/new.html.twig', [
            'option' => $option,
            'form' => $form,
        ]);
    }

    #[Route('/{optionId}', name: 'app_options_show', methods: ['GET'])]
    public function show(Options $option): Response
    {
        return $this->render('options/show.html.twig', [
            'option' => $option,
        ]);
    }

    #[Route('/{optionId}/edit', name: 'app_options_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Options $option, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OptionsType::class, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_options_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('options/edit.html.twig', [
            'option' => $option,
            'form' => $form,
        ]);
    }

    #[Route('/{optionId}', name: 'app_options_delete', methods: ['POST'])]
    public function delete(Request $request, Options $option, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$option->getOptionId(), $request->request->get('_token'))) {
            $entityManager->remove($option);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_options_index', [], Response::HTTP_SEE_OTHER);
    }
}
