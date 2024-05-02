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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Mime\Address;

#[Route('/emploi')]
class EmploiController extends AbstractController
{



    #[Route('/', name: 'app_emploi_index', methods: ['GET'])]
    public function index(
        EmploiRepository $emploiRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {

        $order = $request->query->get('type', 'Croissant');
    
        $emplois = ($order == 'Croissant') ? $emploiRepository->findByAsc() : $emploiRepository->findByDesc();
        

        $premierdates = $emploiRepository->createQueryBuilder('e')
            ->select('DISTINCT e.premierdate')
            ->orderBy('e.premierdate', 'ASC')
            ->getQuery()
            ->getResult();
    
        $selectedPremierdate = $request->query->get('premierdate', '');
        
        if ($selectedPremierdate) {
            $premierdate = DateTime::createFromFormat('Y-m-d', $selectedPremierdate);
        
            $queryBuilder = $emploiRepository->createQueryBuilder('e')
                ->where(':premierdate BETWEEN e.premierdate AND e.dernierdate')
                ->setParameter('premierdate', $premierdate);
            
            $pagination = $paginator->paginate(
                $queryBuilder->getQuery(),
                $request->query->getInt('page', 1),
                5
            );
    
            return $this->render('emploi/index.html.twig', [
                'pagination' => $pagination,
                'premierdates' => $premierdates,
                'selectedPremierdate' => $selectedPremierdate,
                'order' => $order,
            ]);
        }
    
        $pagination = $paginator->paginate(
            $emplois,
            $request->query->getInt('page', 1),
            5
        );
    
        return $this->render('emploi/index.html.twig', [
            'pagination' => $pagination,
            'premierdates' => $premierdates,
            'selectedPremierdate' => $selectedPremierdate,
            'order' => $order, 
        ]);
    }


    #[Route('/pdf', name: 'app_emploi_pdf', methods: ['GET'])]
    public function generatePdf(
        EmploiRepository $emploiRepository,
        Request $request,
        FlasherInterface $flasher
    ): Response {

        $selectedPremierdate = $request->query->get('premierdate', '');
    
        $emplois = [];
        if ($selectedPremierdate) {
            $premierdate = DateTime::createFromFormat('Y-m-d', $selectedPremierdate);
            $emplois = $emploiRepository->findByPremierdate($premierdate);
        } else {
            $emplois = $emploiRepository->findAll();
        }
    
        $html = $this->renderView('emploi/all_emplois_pdf.html.twig', [
            'emplois' => $emplois,
        ]);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
    
        $dompdf->loadHtml($html);
    
        $dompdf->setPaper('A4', 'portrait');
    
        $dompdf->render();
        $flasher->addSuccess('pdf téléchargé avec succès.');

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
        Request $request,
        EmploiRepository $emploiRepository,
        MailerInterface $mailer,
        FlasherInterface $flasher
    ): Response {
        $selectedPremierdate = $request->query->get('premierdate', '');
        
        $emplois = [];
        if ($selectedPremierdate) {
            $premierdate = DateTime::createFromFormat('Y-m-d', $selectedPremierdate);
            $emplois = $emploiRepository->findByPremierdate($premierdate);
        } else {
            $emplois = $emploiRepository->findAll();
        }
    
        $html = $this->renderView('emploi/all_emplois_pdf.html.twig', [
            'emplois' => $emplois
        ]);
    
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $pdfContent = $dompdf->output();
    
        $email = (new Email())
            ->from(new Address('nadineziadi021@gmail.com', 'Scolarité Esprit'))
            ->to('ziadinadine5@gmail.com')
            ->subject('Votre Emploi')
            ->html('<p>Ici, vous trouverez votre emploi du temps pour la semaine. Bonne étude !</p>')
            ->attach($pdfContent, 'emplois.pdf', 'application/pdf');
        $mailer->send($email);
    
        $flasher->addSuccess('Email envoyé avec succès.');
    
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
