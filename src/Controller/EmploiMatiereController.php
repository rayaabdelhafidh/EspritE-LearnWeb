<?php

namespace App\Controller;

use App\Entity\Emploi;
use App\Entity\EmploiMatiere;
use App\Form\EmploiMatiereType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Flasher\Prime\FlasherInterface;

#[Route('/emploi/matiere')]
class EmploiMatiereController extends AbstractController
{
    #[Route('/', name: 'app_emploi_matiere_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $emploiMatieres = $entityManager
            ->getRepository(EmploiMatiere::class)
            ->findAll();

        return $this->render('emploi_matiere/index.html.twig', [
            'emploi_matieres' => $emploiMatieres,
        ]);
    }

    #[Route('/new/{emploiId}', name: 'app_emploi_matiere_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Emploi $emploiId, FlasherInterface $flasher): Response
    {
        $emploi = $emploiId;
    
        $emploiMatiere = new EmploiMatiere();
        $emploiMatiere->setEmploi($emploi);
        $form = $this->createForm(EmploiMatiereType::class, $emploiMatiere);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the EmploiMatiere
            $entityManager->persist($emploiMatiere);
            $entityManager->flush();
           // $flasher->addSuccess('emploi ajoutée avec succès.');
      
            return $this->redirectToRoute('app_emploi_matiere_new', ['emploiId' => $emploiId->getId()]);
         
        }
    
        return $this->renderForm('emploi_matiere/new.html.twig', [
            'emploi_matiere' => $emploiMatiere,
            'form' => $form,
        ]);
      
    }
    
    #[Route('/{emploiId}/edit', name: 'app_emploi_matiere_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EmploiMatiere $emploiMatiere, EntityManagerInterface $entityManager, FlasherInterface $flasher): Response
    {
        $emploi = $emploiMatiere->getEmploi(); // Get the emploi associated with emploiMatiere
        $form = $this->createForm(EmploiMatiereType::class, $emploiMatiere);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $flasher->addSuccess('emploi modifié avec succès.');
    
            return $this->redirectToRoute('app_emploi_matiere_edit', ['emploiId' => $emploi->getId()]);
        }
    
        return $this->renderForm('emploi_matiere/edit.html.twig', [
            'emploi_matiere' => $emploiMatiere,
            'form' => $form,
        ]);
    }
    


    #[Route('/{emploi}', name: 'app_emploi_matiere_show', methods: ['GET'])]
    public function show(EmploiMatiere $emploiMatiere): Response
    {
        return $this->render('emploi_matiere/show.html.twig', [
            'emploi_matiere' => $emploiMatiere,
        ]);
    }

  

    #[Route('/{emploi}', name: 'app_emploi_matiere_delete', methods: ['POST'])]
    public function delete(Request $request, EmploiMatiere $emploiMatiere, EntityManagerInterface $entityManager, FlasherInterface $flasher): Response
    {
        if ($this->isCsrfTokenValid('delete'.$emploiMatiere->getEmploi(), $request->request->get('_token'))) {
            $entityManager->remove($emploiMatiere);
            $entityManager->flush();
            $flasher->addSuccess('emploi supprimé avec succès.');
        }

        return $this->redirectToRoute('app_emploi_matiere_index', [], Response::HTTP_SEE_OTHER);
    }
}
