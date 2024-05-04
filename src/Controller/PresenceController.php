<?php

namespace App\Controller;

use App\Entity\Presence;
use App\Form\PresenceType;
use App\Repository\ClasseRepository;
use App\Repository\PresenceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


#[Route('/presence')]
class PresenceController extends AbstractController
{

    #[Route('/mail', name: 'app_mail', methods: ['GET'])]
    public function sendEmail(MailerInterface $mailer,PresenceRepository $presenceRepository, UserRepository $userRepository): Response
{
    $idetudtot = [];
    foreach( $presenceRepository->findAll() as $pres  ){
        foreach(explode("," , $pres->getIdEtudiants()) as $ide){
            
            $idetud[] = intval($ide);
        }
        array_pop($idetud);
        foreach($idetud as $iid){
            $name[]  = $userRepository->find($iid)->getNom();
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

    #[Route('/', name: 'app_presence_index', methods: ['GET'])]
    public function index(PresenceRepository $presenceRepository , UserRepository $userRepository): Response
    {
        $idetudtot = [];
        foreach( $presenceRepository->findAll() as $pres  ){
            foreach(explode("," , $pres->getIdEtudiants()) as $ide){
                
                $idetud[] = intval($ide);
            }
            array_pop($idetud);
            foreach($idetud as $iid){
                $name[]  = $userRepository->find($iid)->getNom();
            }
            $idetudtot[]=$name;
            $idetud =[]; 
            $name=[] ; 
        }
        return $this->render('presence/index.html.twig', [
            'presences' => $presenceRepository->findAll(),
            'nometu'=>$idetudtot,
        ]);
    }

    #[Route('/new', name: 'app_presence_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager ,ClasseRepository $classeRepository): Response
    {
        $presence = new Presence();
        $form = $this->createForm(PresenceType::class, $presence);
        $form->handleRequest($request);

        

        if ($form->isSubmitted() && $form->isValid()) {
            $listet = $request->get('inputid');
            if($listet !=''){
                $presence->setIdEtudiants($listet);
                $entityManager->persist($presence);
                $entityManager->flush();
                flash()->addSuccess('presence effectuée avec succés');


            return $this->redirectToRoute('app_presence_index', [], Response::HTTP_SEE_OTHER);
            }
            if($presence->getIdClasse()){
                return $this->renderForm('presence/new.html.twig', [
                    'presence' => $presence,
                    'form' => $form,
                    'etudiants' => $classeRepository->find($presence->getIdClasse()->getId())->getUsers()
                ]);
            }


        }

        return $this->renderForm('presence/new.html.twig', [
            'presence' => $presence,
            'form' => $form,
            'etudiants' =>[],
        ]);
    }

    #[Route('/{id}', name: 'app_presence_show', methods: ['GET'])]
    public function show(Presence $presence): Response
    {
        return $this->render('presence/show.html.twig', [
            'presence' => $presence,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_presence_edit', methods: ['GET', 'POST'])]
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

    #[Route('/{id}', name: 'app_presence_delete', methods: ['POST'])]
    public function delete(Request $request, Presence $presence, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$presence->getId(), $request->request->get('_token'))) {
            $entityManager->remove($presence);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_presence_index', [], Response::HTTP_SEE_OTHER);
    }
}
