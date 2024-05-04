<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ClasseRepository;
use App\Repository\PresenceRepository;
use App\Entity\Classe;
use App\Repository\UserRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use phpDocumentor\Reflection\Types\Integer;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ClasseRepository $classeRepository): Response
    {
        // Fetch all classes from the database
        $classes = $classeRepository->findAll();

        return $this->render('home/index.html.twig', [
            'classes' => $classes,
        ]);
    }

    #[Route('/class/{id}/presences', name: 'class_presences')]
    public function classPresences(int $id, Classe $classe ,  PresenceRepository $presenceRepository,ClasseRepository $classeRepository,UserRepository $userRepository): Response
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

        
        return $this->render('home/presence.html.twig', [
            'presences' => $classeRepository->find($id)->getPresences(),
            'classe' => $classeRepository->find($id),
            'nometu'=>$idetudtot,
        ]);
    }


    #[Route('/presencesh/{id}', name: 'presence_pdf')]
    public function pdfPresences(int $id,   PresenceRepository $presenceRepository,ClasseRepository $classeRepository,UserRepository $userRepository): Response
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
            $html = $this->renderView('home/presencepdf.html.twig',
                [
                    'presences' => $classeRepository->find($id)->getPresences(),
                    'classe' => $classeRepository->find($id),
                    'nometu'=>$idetudtot,
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
            $pdfFilepath ="pdfPresence.pdf";
            file_put_contents($pdfFilepath, $output);
        
            return $this->redirectToRoute('class_presences',  ['id' => $id], Response::HTTP_SEE_OTHER);

    }



}
