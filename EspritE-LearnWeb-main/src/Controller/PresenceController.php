<?php

namespace App\Controller;

use App\Entity\Presence;
use App\Form\PresenceType;
use App\Repository\ClasseRepository;
use App\Repository\PresenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Dompdf\Dompdf;
use Dompdf\Options;
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
    

    #[Route('/mail', name: 'app_mail', methods: ['GET'])]
    public function sendEmail(MailerInterface $mailer,PresenceRepository $presenceRepository, ClasseRepository $userRepository): Response
{
    $idetudtot = [];
    foreach( $presenceRepository->findAll() as $pres  ){
        foreach(explode("," , $pres->getIdpresence()) as $ide){
            
            $idetud[] = intval($ide);
        }
        array_pop($idetud);
        foreach($idetud as $iid){
            $name[]  = $userRepository->find($iid)->getNomClasse
            ();
        }
        $idetudtot[]=$name;
        $idetud =[]; 
        $name=[] ; 
    }

    $pdfOptions = new Options();
    $pdfOptions->set('defaultFont', 'Arial');
    $dompdf = new Dompdf($pdfOptions);
        //$abonnement=$AbonnementRepository->findAll();

        //$html2pdf = new Html2Pdf();

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('presence/pdf.html.twig',
            [
                "presences"=> $presenceRepository->findAll(),
                "nometu"=>$idetudtot,
                ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        //$html2pdf->writeHTML($html);

        //$html2pdf->output('myPdf.pdf');

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');


        // Render the HTML as PDF
        $dompdf->render();
        $output = $dompdf->output();
        $pdfFilepath ="test.pdf";
        file_put_contents($pdfFilepath, $output);
        
        $email = (new Email())
        ->from('omaimabhd3@gmail.com')
        ->to('baymarou@gmail.com')
        ->subject('liste des presences')
        ->text('This is a test email sent from Symfony Mailer.')
        ->attach($output,"Presences.pdf");

    $mailer->send($email);
    flash()->addSuccess('mail envoyé avec succés');
      
    return $this->redirectToRoute('app_presence_index', [], Response::HTTP_SEE_OTHER);
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
