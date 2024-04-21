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
    #[Route('/{quizId}/submit', name: 'app_quizz_submit', methods: ['POST'])]
    public function submitt(Request $request, Quizz $quizz, EntityManagerInterface $entityManager): Response
    {
        $submittedAnswers = $request->request->all();
        $totalScore = 0;
        foreach ($submittedAnswers as $questionid => $selectedOptionId) {
            
            $question = $entityManager->getRepository(Question::class)->find($questionid);
          // Check the question ID
            $selectedOption = $entityManager->getRepository(Options::class)->find($selectedOptionId);
            
            // Check if $question and $selectedOption are not null
            if ($question !== null && $selectedOption !== null) {
                // Check if the selected option is correct for this question
                if ($selectedOption->isIsCorrect()) {
                    $totalScore += $question->getScore();
                    
                }
            }
        }
        
        // You can save the score in the database or simply display it in a view
        return $this->render('quizz/submit.html.twig', [
            'totalScore' => $totalScore,
        ]);
     

    }
    #[Route('/submit/{quizId}', name: 'app_quizz_submit', methods: ['POST'])]
    public function submit(Request $request, Quizz $quizz, EntityManagerInterface $entityManager, OptionsRepository $optionsRepository): Response
    {
        // Récupérer les données soumises
        $submittedData = $request->request->all();
    
        // Initialiser le score total à 0
        $totalScore = 0;
    
        // Parcourir les données soumises
        foreach ($submittedData as $questionid => $optionId) {
            // Récupérer l'option sélectionnée
            $option = $optionsRepository->find($optionId);
    
            // Vérifier si l'option existe et si elle est correcte
            if ($option && $option->isIsCorrect()) {
                // Incrémenter le score total
                $question = $option->getQuestion();
            $totalScore += $question->getScore();
            }
        }
    
        // Rediriger vers la page de résultat avec le score total
        return $this->render('quizz/submit.html.twig', [
            'totalScore' => $totalScore,
        ]);

    }
}
