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
use Knp\Component\Pager\PaginatorInterface;
#[Route('/matiere')]
class MatiereController extends AbstractController
{
   
    #[Route('/', name: 'app_matiere_index', methods: ['GET'])]
public function index(Request $request, MatiereRepository $matiereRepository, PaginatorInterface $paginator): Response
{
    $sort = $request->query->get('sort', 'asc'); // Par défaut, tri ascendant

    // Récupérer toutes les matières depuis le repository
    if ($sort === 'asc') {
        $matieres = $matiereRepository->findBy([], ['nomm' => 'ASC']);
    } elseif ($sort === 'desc') {
        $matieres = $matiereRepository->findBy([], ['nomm' => 'DESC']);
    } else {
        $matieres = $matiereRepository->findAll();
    }

    // Paginer les résultats triés
    $matieresPaginated = $paginator->paginate(
        $matieres, // Les résultats à paginer
        $request->query->getInt('page', 1), // Numéro de page à afficher
        10 // Nombre d'éléments par page
    );

    return $this->render('matiere/index.html.twig', [
        'matieres' => $matieresPaginated,
    ]);
}

#[Route('/matiereEtud', name: 'app_matiere_indexEtud', methods: ['GET'])]
public function indexEtud(Request $request, MatiereRepository $matiereRepository): Response
{
    $sort = $request->query->get('sort', 'asc'); // Par défaut, tri ascendant

    // Récupérer toutes les matières depuis le repository
    if ($sort === 'asc') {
        $matieres = $matiereRepository->findBy([], ['nomm' => 'ASC']);
    } elseif ($sort === 'desc') {
        $matieres = $matiereRepository->findBy([], ['nomm' => 'DESC']);
    } else {
        $matieres = $matiereRepository->findAll();
    }

    return $this->render('matiere/indexEtud.html.twig', [
        'matieres' => $matieres,
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
        
        // Mettre à jour les attributs de Plandetude en fonction des valeurs de la matière
        $plandetude->setDureetotal($plandetude->getDureetotal() + $matiere->getNbrHeure());
        $plandetude->setCreditsrequistotal($plandetude->getCreditsrequistotal() + $matiere->getCredit());
        
        // Persister les changements de l'objet Plandetude
        $entityManager->persist($plandetude);

        // Associer la matière au plan d'étude
        $matiere->setIdplandetude($plandetude);
        
        // Persister la matière en base de données
        $entityManager->persist($matiere);
        $entityManager->flush();
        flash()->addSuccess('matiere ajouter  avec succes');
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
    $originalNbrHeure = $matiere->getNbrHeure();
    $originalCredit = $matiere->getCredit();

    $form = $this->createForm(MatiereType::class, $matiere);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer l'ID du plan d'étude sélectionné depuis le formulaire
        $idPlandetude = $form->get('idplandetude')->getData();
        
        // Récupérer l'entité Plandetude correspondante depuis la base de données
        $plandetude = $this->getDoctrine()->getRepository(Plandetude::class)->find($idPlandetude);
        
        // Calculer les différences de durée totale et de crédits requis
        $diffDuree = $matiere->getNbrHeure() - $originalNbrHeure;
        $diffCredit = $matiere->getCredit() - $originalCredit;

        // Mettre à jour les attributs de Plandetude en fonction des différences calculées
        $plandetude->setDureetotal($plandetude->getDureetotal() + $diffDuree);
        $plandetude->setCreditsrequistotal($plandetude->getCreditsrequistotal() + $diffCredit);
        
        // Persister les changements de l'objet Plandetude
        $entityManager->persist($plandetude);

        // Persister la matière en base de données
        $entityManager->flush();
        flash()->addSuccess('matiere modifier  avec succes');
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
        flash()->addSuccess('matiere supprimer   avec succes');
        return $this->redirectToRoute('app_matiere_index', [], Response::HTTP_SEE_OTHER);
    }
}
