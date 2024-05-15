<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use App\Repository\QuizzRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Quizz;
use App\Entity\Options;
use App\Repository\OptionsRepository;
use Flasher\Prime\FlasherInterface;
use Flashy\Flashy;
use Knp\Component\Pager\PaginatorInterface;


#[Route('/question')]
class QuestionController extends AbstractController
{
    #[Route('/', name: 'app_question_index', methods: ['GET'])]
    public function index(Request $request, QuestionRepository $questionRepository, PaginatorInterface $paginator): Response
    {
        $sort = $request->query->get('sort');
        if ($sort === 'asc') {
            $questions = $questionRepository->findBy([], ['content' => 'ASC']);
        } elseif ($sort === 'desc') {
            $questions = $questionRepository->findBy([], ['content' => 'DESC']);
        } else {
            $questions = $questionRepository->findAll();
        }

        $questionsPaginated = $paginator->paginate(
            $questions, // Les résultats à paginer
            $request->query->getInt('page', 1), // Numéro de page à afficher
            3
        );

        return $this->render('question/index.html.twig', [
            'questions' =>$questionsPaginated,
        ]);
    }

    #[Route('/new', name: 'app_question_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FlasherInterface $flasher): Response
    { $quizId = $request->query->getInt('quizId');
        $quiz = $this->getDoctrine()->getRepository(Quizz::class)->find($quizId);
                // Make sure the quiz exists
                if (!$quiz) {
                    throw $this->createNotFoundException('Quiz not found');
                }

        $question = new Question();
        $question->setQuiz($quiz);
        $form = $this->createForm(QuestionType::class, $question);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($question);
            $entityManager->flush();
            $flasher->addSuccess('Question ajoutée avec succès.');

            return $this->redirectToRoute('app_question_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('question/new.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }



    #[Route('/{questionid}', name: 'app_question_show', methods: ['GET'])]
    public function show(Question $question): Response
    {
        return $this->render('question/show.html.twig', [
            'question' => $question,
        ]);
    }

    #[Route('/{questionid}/edit', name: 'app_question_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Question $question, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_question_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('question/edit.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }

    #[Route('/{questionid}', name: 'app_question_delete', methods: ['POST'])]
    public function delete(Request $request, Question $question, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getQuestionid(), $request->request->get('_token'))) {
            $entityManager->remove($question);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_question_index', [], Response::HTTP_SEE_OTHER);
    }
}
