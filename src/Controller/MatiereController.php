<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Matiere;
use App\Entity\Plandetude;
use App\Form\MatiereType;
use App\Repository\MatiereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/matiere')]
class MatiereController extends AbstractController
{
    #[Route('/', name: 'app_matiere_index', methods: ['GET'])]
    public function index(MatiereRepository $matiereRepository): Response
    {
        return $this->render('matiere/index.html.twig', [
            'matieres' => $matiereRepository->findAll(),
        ]);
    }
    #[Route('/matiereEtud', name: 'app_matiere_indexEtud', methods: ['GET'])]
    public function indexEtud(MatiereRepository $matiereRepository): Response
    {
        return $this->render('matiere/indexEtud.html.twig', [
            'matieres' => $matiereRepository->findAll(),
        ]);
    }

    /*#[Route('/new', name: 'app_matiere_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $matiere = new Matiere();
        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             // Récupérer l'ID de la matière sélectionnée depuis le formulaire
             $idPlandetude = $form->get('idplandetude')->getData();
            
             // Récupérer l'entité Matiere correspondante depuis la base de données
             $plandetude = $this->getDoctrine()->getRepository(Plandetude::class)->find($idPlandetude);
             
             // Associer la matière au cours
             $matiere->setIdplandetude($plandetude);
            $entityManager->persist($plandetude);
            $entityManager->flush();

            return $this->redirectToRoute('app_matiere_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('matiere/new.html.twig', [
            'matiere' => $matiere,
            'form' => $form,
        ]);
    }*/
   /* #[Route('/new', name: 'app_matiere_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $matiere = new Matiere();
    $form = $this->createForm(MatiereType::class, $matiere);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($matiere); // Utilisez persist sur l'entité Matiere
        $entityManager->flush();

        return $this->redirectToRoute('app_matiere_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('matiere/new.html.twig', [
        'matiere' => $matiere,
        'form' => $form,
    ]);
}*/
#[Route('/new', name: 'app_matiere_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $matiere = new Matiere();
    $form = $this->createForm(MatiereType::class, $matiere);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer l'ID du plan d'étude sélectionné depuis le formulaire
        $idPlandetude = $form->get('idplandetude')->getData();
        
        // Récupérer l'entité Plandetude correspondante depuis la base de données
        $plandetude = $this->getDoctrine()->getRepository(Plandetude::class)->find($idPlandetude);
        
        // Associer la matière au plan d'étude
        $matiere->setIdplandetude($plandetude);
        
        // Persister la matière en base de données
        $entityManager->persist($matiere);
        $entityManager->flush();

        return $this->redirectToRoute('app_matiere_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('matiere/new.html.twig', [
        'matiere' => $matiere,
        'form' => $form,
    ]);
}

#[Route('/print', name: 'app_matiere_print', methods: ['GET'])]
public function print( MatiereRepository $matiererepository)
{

    $result = $matiererepository->findAll();
    $pdfOptions = new Options();

    // Instantiate Dompdf with our options
    $dompdf = new Dompdf($pdfOptions);

    // Retrieve the HTML generated in our twig file
    $html = $this->renderView('matiere/print.html.twig', [
        'matieres' => $result
    ]);

    // Load HTML to Dompdf
    $dompdf->loadHtml($html);
    // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();
    // Output the generated PDF as a response with Content-Type set to 'application/pdf'
    return new Response($dompdf->output(), Response::HTTP_OK, [
        'Content-Type' => 'application/pdf',
    ]);

}

    #[Route('/{idm}', name: 'app_matiere_show', methods: ['GET'])]
    public function show(Matiere $matiere): Response
    {
        return $this->render('matiere/show.html.twig', [
            'matiere' => $matiere,
        ]);
    }

    #[Route('/{idm}/edit', name: 'app_matiere_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Matiere $matiere, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'ID du plan d'étude sélectionné depuis le formulaire
        $idPlandetude = $form->get('idplandetude')->getData();
        
        // Récupérer l'entité Plandetude correspondante depuis la base de données
        $plandetude = $this->getDoctrine()->getRepository(Plandetude::class)->find($idPlandetude);
        
        // Associer la matière au plan d'étude
        $matiere->setIdplandetude($plandetude);
        
        // Persister la matière en base de données
        $entityManager->persist($matiere);
            $entityManager->flush();

            return $this->redirectToRoute('app_matiere_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('matiere/edit.html.twig', [
            'matiere' => $matiere,
            'form' => $form,
        ]);
    }


   
    


    #[Route('/{idm}', name: 'app_matiere_delete', methods: ['POST'])]
    public function delete(Request $request, Matiere $matiere, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$matiere->getIdm(), $request->request->get('_token'))) {
            $entityManager->remove($matiere);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_matiere_index', [], Response::HTTP_SEE_OTHER);
    }
}
