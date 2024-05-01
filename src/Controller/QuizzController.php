<?php

namespace App\Controller;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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
use Symfony\Component\HttpFoundation\JsonResponse;

use BaconQrCode\Common\Mode;
use App\Services\QrcodeService;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\ErrorCorrectionLevel;

use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCodeBundle\Response\QrCodeResponse;

use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options as DompdfOptions;
use ZipArchive;
use App\Entity\Reponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

#[Route('/quizz')]
class QuizzController extends AbstractController
{

    #[Route('/', name: 'app_quizz_index', methods: ['GET'])]
    public function index(Request $request, QuizzRepository $quizRepository,PaginatorInterface $paginator): Response
    {
        $sort = $request->query->get('sort');
        if ($sort === 'asc') {
            $quizzs = $quizRepository->findBy([], ['description' => 'ASC']);
        } elseif ($sort === 'desc') {
            $quizzs = $quizRepository->findBy([], ['description' => 'DESC']);
        } else {
            $quizzs = $quizRepository->findAll();
        }
        

        return $this->render('quizz/index.html.twig', [
            'quizzs' => $quizzs,
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
    public function submit(Request $request, Quizz $quizz, EntityManagerInterface $entityManager, OptionsRepository $optionsRepository, QrcodeService $qrcodeService): Response
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
    
        $qrCodeData = $qrcodeService->qrcode('Total Score: ' . $totalScore);
    
        // Pass the total score and QR code data to the view
        return $this->render('quizz/submit.html.twig', [
            'totalScore' => $totalScore,
            'qrCodeData' => $qrCodeData,  
            'quizz' => $quizz, // Make sure quizz is passed here         
        ]);
       
    }
 
    #[Route('/submit/{quizId}/pdf', name: 'app_quizz_submit_pdf', methods: ['GET'])]
    public function generatePdf(Request $request, Quizz $quizz): Response
    {
        $html = $this->renderView('quizz/submit.html.twig', [
            'totalScore' => $request->request->get('totalScore'),
            'qrCodeData' => $request->request->get('qrCodeData'),
            'quizz' => $quizz,
        ]);

        // Configure Dompdf
        $options = new DompdfOptions();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        // Instantiate Dompdf
        $dompdf = new Dompdf($options);

        // Load HTML content
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the PDF
        $dompdf->render();

        // Output the generated PDF and force download
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="quiz_result.pdf"',
            ]
        );
    }
    #[Route('/pdf/{quizId}', name: 'app_emploi_pdf', methods: ['GET'])]
    public function generatedf(Quizz $quizz, QuestionRepository $questionRepository): Response
    {
        // Fetch all emplois from the database
        $questions = $questionRepository->findBy(['quiz' => $quizz]);
    
        // Render the PDF template
        $html = $this->renderView('quizz/sumbitpdf.html.twig', [
            'quizz' => $quizz,
            'questions' => $questions,
        ]);
    
        // Configure Dompdf
        $options = new DompdfOptions();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
    
        // Instantiate Dompdf
        $dompdf = new Dompdf($options);
    
        // Load HTML content
        $dompdf->loadHtml($html);
    
        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');
    
        // Render the PDF
        $dompdf->render();
    
        // Output the generated PDF and force download
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="Quiz.pdf"',
            ]
        );
    }
    #[Route('/pdf/{quizId}/send', name: 'app_emploi_pdf_send', methods: ['GET'])]
    public function generatePdfAndSendEmail(
        Quizz $quizz,
         QuestionRepository $questionRepository,
        Request $request,
        MailerInterface $mailer
    ): Response {
        $questions = $questionRepository->findBy(['quiz' => $quizz]);
    
        // Render the PDF template
        $html = $this->renderView('quizz/sumbitpdf.html.twig', [
            'quizz' => $quizz,
            'questions' => $questions,
        ]);
    
    
  // Configure Dompdf
  $options = new DompdfOptions();
  $options->set('isHtml5ParserEnabled', true);
  $options->set('isRemoteEnabled', true);

  // Instantiate Dompdf
  $dompdf = new Dompdf($options);

  // Load HTML content
  $dompdf->loadHtml($html);

  // Set paper size and orientation
  $dompdf->setPaper('A4', 'portrait');

  // Render the PDF
  $dompdf->render();

    
        // Output the generated PDF content
        $pdfContent = $dompdf->output();
    
        // Send email with attachment
        $email = (new Email())
            ->from('wael.benhamouda14@gmail.com')
            ->to('eya.b.hamouda@gmail.com')
            ->subject('Emploi PDF')
            ->html('<p>Please find the emploi PDF attached.</p>')
            ->attach($pdfContent, 'emplois.pdf', 'application/pdf');
    
        $mailer->send($email);
    
        // Redirect back to the index page or any other page
        return $this->redirectToRoute('app_quizz_index');
    }
    

    
    }

