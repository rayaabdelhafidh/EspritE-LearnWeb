<?php

namespace App\Controller;

use App\Entity\Quizz;
use App\Form\QuizzType;
use App\Repository\QuizzRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use App\Entity\Options;
use App\Form\OptionsType;
use App\Repository\OptionsRepository;


#[Route('/quizz')]
class QuizzController extends AbstractController
{
    #[Route('/', name: 'app_quizz_index', methods: ['GET'])]
    public function index(QuizzRepository $quizzRepository): Response
    {
        return $this->render('quizz/index.html.twig', [
            'quizzs' => $quizzRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_quizz_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $quizz = new Quizz();
        $form = $this->createForm(QuizzType::class, $quizz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($quizz);
            $entityManager->flush();

            return $this->redirectToRoute('app_quizz_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quizz/new.html.twig', [
            'quizz' => $quizz,
            'form' => $form,
        ]);
    }

    #[Route('/{quizId}', name: 'app_quizz_show', methods: ['GET'])]
    public function show(Quizz $quizz, QuestionRepository $questionRepository): Response
    {
        // Fetch the questions associated with the quiz
        $questions = $questionRepository->findBy(['quiz' => $quizz]);
    
        return $this->render('quizz/show.html.twig', [
            'quizz' => $quizz,
            'questions' => $questions,
        ]);
    }
    #[Route('/{quizId}/edit', name: 'app_quizz_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quizz $quizz, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuizzType::class, $quizz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_quizz_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quizz/edit.html.twig', [
            'quizz' => $quizz,
            'form' => $form,
        ]);
    }


    #[Route('/{quizId}', name: 'app_quizz_delete', methods: ['POST'])]
    public function delete(Request $request, Quizz $quizz, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quizz->getQuizId(), $request->request->get('_token'))) {
            $entityManager->remove($quizz);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_quizz_index', [], Response::HTTP_SEE_OTHER);
    }
}
