<?php

namespace App\Controller;

use App\Entity\Cour;
use App\Entity\Matiere;
use App\Form\CourType;
use App\Repository\CourRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/cour')]
class CourController extends AbstractController
{
    #[Route('/', name: 'app_cour_index', methods: ['GET'])]
    public function index(CourRepository $courRepository): Response
    {
        // Récupérer les cours par défaut
        $cours = $courRepository->findAll();

        return $this->render('cour/index.html.twig', [
            'cours' => $cours,
        ]);
    }

    #[Route('/sort', name: 'app_cour_sort', methods: ['GET'])]
    public function sort(Request $request, CourRepository $courRepository): Response
    {
        $attribute = $request->query->get('attribute');
        $order = $request->query->get('order');

        // Vérifier si les paramètres de tri sont valides
        if (!in_array($attribute, ['titre', 'duree', 'description'])) {
            throw new \InvalidArgumentException('Invalid sorting attribute.');
        }

        if (!in_array($order, ['asc', 'desc'])) {
            throw new \InvalidArgumentException('Invalid sorting order.');
        }

        // Récupérer les cours triés
        $cours = $courRepository->findBy([], [$attribute => $order]);

        return $this->render('cour/index.html.twig', [
            'cours' => $cours,
        ]);
    }
    #[Route('/courEtud', name: 'app_cour_indexEtud', methods: ['GET'])]
    public function indexEtud(Request $request, CourRepository $courRepository): Response
    {
        $sort = $request->query->get('sort');

        if ($sort === 'asc') {
            $cours = $courRepository->findBy([], ['titre' => 'ASC']);
        } elseif ($sort === 'desc') {
            $cours = $courRepository->findBy([], ['titre' => 'DESC']);
        } else {
            $cours = $courRepository->findAll();
        }

        return $this->render('cour/indexEtud.html.twig', [
            'cours' => $cours,
        ]);
    }
    

   
    /*#[Route('/new', name: 'app_cour_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cour = new Cour();
        $form = $this->createForm(CourType::class, $cour);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {

          $file = $form->get('image')->getData();
            if ($file) {
                // Generate a unique name for the file before saving it
                $fileName = md5(uniqid()).'.'.$file->guessExtension();


                // Move the file to the directory where brochures are stored
                $targetDirectory = $this->getParameter('kernel.project_dir') . '/public';
                $file->move(
                    $targetDirectory,
                    $fileName
                );
                $cour->setImage($fileName);
            }
            // Récupérer l'ID de la matière sélectionnée depuis le formulaire
            $idMatiere = $form->get('idmatiere')->getData();
            
            // Récupérer l'entité Matiere correspondante depuis la base de données
            $matiere = $this->getDoctrine()->getRepository(Matiere::class)->find($idMatiere);
            
            // Associer la matière au cours
            $cour->setIdmatiere($matiere);
            
            // Enregistrer le cours en base de données
            $entityManager->persist($cour);
            $entityManager->flush();
            flash()->addSuccess('cour Added Successfully');
            return $this->redirectToRoute('app_cour_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('cour/new.html.twig', [
            'cour' => $cour,
            'form' => $form,
        ]);
    }*/
   

    #[Route('/new', name: 'app_cour_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $cour = new Cour();
    // Définir le nombre de likes et la note à 0 par défaut
    $cour->setNblike(0);
    $cour->setNote(0);

    $form = $this->createForm(CourType::class, $cour);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Gérer le téléchargement du fichier PDF
        $pdfFile = $form->get('coursPdfUrl')->getData();
        if ($pdfFile instanceof UploadedFile) {
            $pdfFileName = md5(uniqid()) . '.' . $pdfFile->guessExtension();
            $pdfFile->move(
                $this->getParameter('kernel.project_dir') . '/public/uploads/pdf',
                $pdfFileName
            );
            $cour->setCourspdfurl($pdfFileName);
        }

        $file = $form->get('image')->getData();
        if ($file) {
            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            $targetDirectory = $this->getParameter('kernel.project_dir') . '/public';
            $file->move(
                $targetDirectory,
                $fileName
            );
            $cour->setImage($fileName);
        }
        // Récupérer l'ID de la matière sélectionnée depuis le formulaire
        $idMatiere = $form->get('idmatiere')->getData();

        // Récupérer l'entité Matiere correspondante depuis la base de données
        $matiere = $this->getDoctrine()->getRepository(Matiere::class)->find($idMatiere);

        // Associer la matière au cours
        $cour->setIdmatiere($matiere);

        // Enregistrer le cours en base de données
        $entityManager->persist($cour);
        $entityManager->flush();
        flash()->addSuccess('cour Added Successfully');
        return $this->redirectToRoute('app_cour_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('cour/new.html.twig', [
        'cour' => $cour,
        'form' => $form,
    ]);
}




    
    #[Route('/{id}', name: 'app_cour_show', methods: ['GET'])]
    public function show(Cour $cour): Response
    {
        return $this->render('cour/show.html.twig', [
            'cour' => $cour,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cour_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cour $cour, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CourType::class, $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            if ($file) {
                // Generate a unique name for the file before saving it
                $fileName = md5(uniqid()).'.'.$file->guessExtension();


                // Move the file to the directory where brochures are stored
                $targetDirectory = $this->getParameter('kernel.project_dir') . '/public';
                $file->move(
                    $targetDirectory,
                    $fileName
                );
                $cour->setImage($fileName);
            }
            // Récupérer l'ID de la matière sélectionnée depuis le formulaire
            $idMatiere = $form->get('idmatiere')->getData();
            // Récupérer l'entité Matiere correspondante depuis la base de données
            $matiere = $this->getDoctrine()->getRepository(Matiere::class)->find($idMatiere);
            
            // Associer la matière au cours
            $cour->setIdmatiere($matiere);
            
            $entityManager->flush();
            flash()->addSuccess('cour Modified Successfully');

            return $this->redirectToRoute('app_cour_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cour/edit.html.twig', [
            'cour' => $cour,
            'form' => $form,
        ]);
    }
    #[Route('/download-pdf/{id}', name: 'download_pdf')]
    public function downloadPdf($id, CourRepository $repo): Response
    {
        $candidature = $repo->find($id);
        if (!$candidature) {
            throw $this->createNotFoundException('Candidature non trouvée');
        }
    
        // Chemin du fichier PDF
        //$pdfPath = $this->getParameter('pdf_directory') . '/' . $candidature->getCourspdfurl();
        $pdfPath = $this->getParameter('pdf_directory') . '/' . $candidature->getCourspdfurl();

        // Créer la réponse
        $response = new BinaryFileResponse($pdfPath);
    
        // Définir les en-têtes pour indiquer qu'il s'agit d'un téléchargement de fichier PDF
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $candidature->getCourspdfurl()
        ));
    
        return $response;
    }

     #[Route('/like/{id}', name: 'app_cour_like', methods: ['POST'])]
public function like(Request $request, Cour $cour, EntityManagerInterface $entityManager): Response
{
    // Récupérer le nombre de likes actuel
    $nbLikes = $cour->getNblike();
    
    // Incrémenter le nombre de likes
    $cour->setNblike($nbLikes + 1);
    
    // Enregistrer les modifications dans la base de données
    $entityManager->persist($cour);
    $entityManager->flush();
    
    // Retourner une réponse JSON avec le nouveau nombre de likes
    return new JsonResponse(['nb_likes' => $cour->getNblike()]);
}

#[Route('/dislike/{id}', name: 'app_cour_dislike', methods: ['POST'])]
public function dislike(Request $request, Cour $cour, EntityManagerInterface $entityManager): Response
{
    // Récupérer le nombre de likes actuel
    $nbLikes = $cour->getNblike();
    
    // Décrémenter le nombre de likes
    $cour->setNblike($nbLikes - 1);
    
    // Enregistrer les modifications dans la base de données
    $entityManager->persist($cour);
    $entityManager->flush();
    
    // Retourner une réponse JSON avec le nouveau nombre de likes
    return new JsonResponse(['nb_likes' => $cour->getNblike()]);
}


    #[Route('/{id}', name: 'app_cour_delete', methods: ['POST'])]
    public function delete(Request $request, Cour $cour, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cour->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cour);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cour_index', [], Response::HTTP_SEE_OTHER);
    }
}
