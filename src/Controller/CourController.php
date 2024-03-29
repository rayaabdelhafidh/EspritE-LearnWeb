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

#[Route('/cour')]
class CourController extends AbstractController
{
    #[Route('/', name: 'app_cour_index', methods: ['GET'])]
    public function index(CourRepository $courRepository): Response
    {
        return $this->render('cour/index.html.twig', [
            'cours' => $courRepository->findAll(),
        ]);
    }
    #[Route('/courEtud', name: 'app_cour_indexEtud', methods: ['GET'])]
    public function indexEtud(CourRepository $courRepository): Response
    {
        return $this->render('cour/indexEtud.html.twig', [
            'cours' => $courRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cour_new', methods: ['GET', 'POST'])]
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

            return $this->redirectToRoute('app_cour_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cour/edit.html.twig', [
            'cour' => $cour,
            'form' => $form,
        ]);
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
