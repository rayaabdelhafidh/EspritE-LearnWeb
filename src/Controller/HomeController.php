<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ClasseRepository;
use App\Repository\PresenceRepository;
use App\Entity\Classe;
use App\Entity\Presence;
use App\Form\PresenceType;
use App\Form\PresenceTypeFront;
use App\Repository\UserRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ClasseRepository $classeRepository): Response
    {
        // Fetch all classes from the database
        $classes = $classeRepository->findAll();

        return $this->render('home/index.html.twig', [
            'classes' => $classes,
            'enseignat'=>false ,

        ]);
    }



    #[Route('/classeFront', name: 'app_home_classe')]
    public function frontClasse(UserRepository $userRepository, ClasseRepository $classeRepository,AuthenticationUtils $authenticationUtils): Response
    {

        if(!$authenticationUtils->getLastUsername() ){
            return $this->redirectToRoute('app_login',  [], Response::HTTP_SEE_OTHER);

        }
        // Fetch all classes from the database
        $classes = [];
        $useremail = $authenticationUtils->getLastUsername();
        $user = $userRepository->findOneBy(['email' => $useremail]);
        $classes[] = $user->getIdClasse();
        if($classes == []){
            $classes = null;
        }
        return $this->render('home/index.html.twig', [
            'classes' => $classes,
            'enseignat'=>$user->getRoles()[0]=="ROLE_ENSEIGNANT",
        ]);
    }

    #[Route('/class/{id}/presences', name: 'class_presences')]
    public function classPresences(int $id, Classe $classe ,  PresenceRepository $presenceRepository,ClasseRepository $classeRepository,UserRepository $userRepository): Response
    {       

        $idetudtot = [];
        foreach( $classeRepository->find($id)->getPresences() as $pres  ){
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
        foreach( $classeRepository->find($id)->getPresences() as $pres  ){
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


    #[Route('/classnew/{id}', name: 'app_presencefront_new', methods: ['GET', 'POST'])]
    public function classnew(int $id,  Request $request, EntityManagerInterface $entityManager ,ClasseRepository $classeRepository): Response
    {

        $classe = $classeRepository->find($id);
        $presence = new Presence();
        $presence->setIdClasse($classe);
        $form = $this->createForm(PresenceTypeFront::class, $presence, [
            'idclassname' => $classe->getNom(),
        ]);

        $form->handleRequest($request);

        

        if ($form->isSubmitted() && $form->isValid()) {
            $listet = $request->get('inputid');
            if($listet !=''){
                $presence->setIdEtudiants($listet);
                $presence->setIdClasse($classe);
                $entityManager->persist($presence);
                $entityManager->flush();
                flash()->addSuccess('presence effectuée avec succés');


            return $this->redirectToRoute('class_presences',  ['id' => $id], Response::HTTP_SEE_OTHER);
            }
            if($presence->getIdClasse()){
                $etud=[];
                foreach($classeRepository->find($presence->getIdClasse()->getId())->getUsers() as $usr){
                    if($usr->getRoles()[0]=="ROLE_ETUDIANT")
                    {
                        $etud[]=$usr;
                    }
                }
                return $this->renderForm('presence/newfront.html.twig', [
                    'presence' => $presence,
                    'form' => $form,
                    'etudiants' => $etud,
                    'class'=> $classe,
                ]);
            }


        }

        return $this->renderForm('presence/newfront.html.twig', [
            'presence' => $presence,
            'form' => $form,
            'etudiants' =>[],
            'class'=> $classe,
        ]);
    }



}
