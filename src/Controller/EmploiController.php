<?php

namespace App\Controller;

use App\Entity\Emploi;
use App\Entity\EmploiMatiere;
use App\Form\EmploiType;
use App\Repository\EmploiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Flasher\Prime\FlasherInterface;
use DateTime;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use BaconQrCode\Encoder\QrCode;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use App\Service\QrCodeGenerator;

#[Route('/emploi')]
class EmploiController extends AbstractController
{
    #[Route('/', name: 'app_emploi_index', methods: ['GET'])]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        // Retrieve all unique premierdates from the Emploi entity
        $premierdates = $this->getDoctrine()
            ->getRepository(Emploi::class)
            ->createQueryBuilder('e')
            ->select('DISTINCT e.premierdate')
            ->orderBy('e.premierdate', 'ASC')
            ->getQuery()
            ->getResult();
    
        // Get the selected premierdate from the request
        $selectedPremierdate = $request->query->get('premierdate');
    
        if ($selectedPremierdate) {
            // Convert string representation back to DateTime object
            $premierdate = DateTime::createFromFormat('Y-m-d', $selectedPremierdate);
        
            // Query emplois with the selected premierdate
            $queryBuilder = $this->getDoctrine()
                ->getRepository(Emploi::class)
                ->createQueryBuilder('e')
                ->where(':premierdate BETWEEN e.premierdate AND e.dernierdate')
                ->setParameter('premierdate', $premierdate);
        
            // Paginate the query results
            $pagination = $paginator->paginate(
                $queryBuilder->getQuery(),
                $request->query->getInt('page', 1),
                5
            );
        
            return $this->render('emploi/index.html.twig', [
                'pagination' => $pagination,
                'premierdates' => $premierdates,
                'selectedPremierdate' => $selectedPremierdate,
            ]);
        }
        
    
        // If no premierdate is selected, render all emplois
        $queryBuilder = $this->getDoctrine()
            ->getRepository(Emploi::class)
            ->createQueryBuilder('e');
        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            5
        );
    
        return $this->render('emploi/index.html.twig', [
            'pagination' => $pagination,
            'premierdates' => $premierdates,
        ]);
    }
    
    
    
    #[Route('/pdf', name: 'app_emploi_pdf', methods: ['GET'])]
    public function generatePdf(EmploiRepository $emploiRepository): Response
    {
        // Fetch all emplois from the database
        $emplois = $emploiRepository->findAll();
    
        // Render the PDF template
        $html = $this->renderView('emploi/all_emplois_pdf.html.twig', [
            'emplois' => $emplois,
        ]);
    
        // Configure Dompdf
        $options = new Options();
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
                'Content-Disposition' => 'attachment; filename="emplois.pdf"',
            ]
        );
    }


    
    #[Route('/pdf/send', name: 'app_emploi_pdf_send', methods: ['GET'])]
public function generatePdfAndSendEmail(
    EmploiRepository $emploiRepository,
    Request $request,
    MailerInterface $mailer
): Response {
    $emplois = $emploiRepository->findAll();

    // Generate PDF content
    $html = $this->renderView('emploi/all_emplois_pdf.html.twig', [
        'emplois' => $emplois
    ]);


    // Configure Dompdf
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
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
        ->from('nadineziadi021@gmail.com')
        ->to('talesbyrory@gmail.com')
        ->subject('Emploi PDF')
        ->html('<p>Please find the emploi PDF attached.</p>')
        ->attach($pdfContent, 'emplois.pdf', 'application/pdf');

    $mailer->send($email);

    // Redirect back to the index page or any other page
    return $this->redirectToRoute('app_emploi_index');
}


    #[Route('/new', name: 'app_emploi_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $emploi = new Emploi();
        $form = $this->createForm(EmploiType::class, $emploi);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($emploi);
            $entityManager->flush();

            $this->get('session')->set('new_empoi_data', $emploi);
    
            return $this->redirectToRoute('app_emploi_matiere_new', ['emploiId' => $emploi->getId()]);
        }
    
        return $this->render('emploi/new.html.twig', [
            'emploi' => $emploi,
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/{id}/edit', name: 'app_emploi_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Emploi $emploi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EmploiType::class, $emploi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_emploi_matiere_edit', ['emploiId' => $emploi->getId()]);
        }

        return $this->renderForm('emploi/edit.html.twig', [
            'emploi' => $emploi,
            'form' => $form,
        ]);
    }

    public function generateQrCode(QrCodeGenerator $qrCodeGenerator, EmploiRepository $emploiRepository): Response
    {
        // Fetch all emplois from the database
        $emplois = $emploiRepository->findAll();
    
        // Render the PDF template
        $html = $this->renderView('emploi/all_emplois_pdf.html.twig', [
            'emplois' => $emplois,
        ]);
    
        // Configure Dompdf
        $options = new Options();
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
    
        // Get the PDF content
        $pdfContent = $dompdf->output();
    
        // Generate the QR code for the PDF content
        $qrCodeImagePath = $qrCodeGenerator->generateQrCodeForPdf($pdfContent);
    
        return $this->render('qr_code.html.twig', [
            'qrCodeImagePath' => $qrCodeImagePath,
        ]);
    }
    

    

    #[Route('/{id}', name: 'app_emploi_show', methods: ['GET'])]
    public function show(Emploi $emploi): Response
    {
        $EmploiMatiere = $this->getDoctrine()->getRepository(EmploiMatiere::class)->findOneBy(['emploi' => $emploi]);
        return $this->render('emploi/show.html.twig', [
            'emploi' => $emploi,
            'EmploiMatiere' => $EmploiMatiere
        ]);
    }

   
    #[Route('/{id}', name: 'app_emploi_delete', methods: ['POST'])]
public function delete(Request $request, Emploi $emploi, EntityManagerInterface $entityManager, FlasherInterface $flasher): Response
{
    if ($this->isCsrfTokenValid('delete'.$emploi->getId(), $request->request->get('_token'))) {
        $emploiMatieres = $entityManager->getRepository(EmploiMatiere::class)->findBy(['emploi' => $emploi]);

        foreach ($emploiMatieres as $emploiMatiere) {
            $entityManager->remove($emploiMatiere);
        }

        $entityManager->remove($emploi);
        $entityManager->flush();
        $flasher->addSuccess('emploi supprimé avec succès.');
    }

    return $this->redirectToRoute('app_emploi_index', [], Response::HTTP_SEE_OTHER);
}
}
