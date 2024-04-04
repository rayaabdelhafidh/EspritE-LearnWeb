<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/evenement')]
class EvenementController extends AbstractController
{
    #[Route('/', name: 'app_evenement_index', methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository): Response
    {
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
        ]);
    }

    #[Route('/evenementEtud', name: 'app_evenement_indexFront', methods: ['GET'])]
    public function indexFront(EvenementRepository $evenementRepository): Response
    {
        return $this->render('evenement/indexFront.html.twig', [
            'evenements' => $evenementRepository->findAll(),
        ]);
    }

    #[Route('/{idevenement}/details', name: 'app_evenement_details', methods: ['GET'])]
    public function details(Evenement $evenement): Response
    {
        return $this->render('evenement/showDetails.html.twig', [
            'evenement' => $evenement,
        ]);
    }


    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                $file = $form->get('afficheevenement')->getData();
                if ($file) {
                    // Generate a unique name for the file before saving it
                    $fileName = md5(uniqid()).'.'.$file->guessExtension();
    
    
                    // Move the file to the directory where brochures are stored
                    $targetDirectory = $this->getParameter('kernel.project_dir') . '/public';
                    $file->move(
                        $targetDirectory,
                        $fileName
                    );
                    $evenement->setAfficheevenement($fileName);
                }
                
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }


    #[Route('/{idevenement}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/{idevenement}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('afficheevenement')->getData();
                if ($file) {
                    // Generate a unique name for the file before saving it
                    $fileName = md5(uniqid()).'.'.$file->guessExtension();
    
    
                    // Move the file to the directory where brochures are stored
                    $targetDirectory = $this->getParameter('kernel.project_dir') . '/public';
                    $file->move(
                        $targetDirectory,
                        $fileName
                    );
                    $evenement->setAfficheevenement($fileName);
                }
                
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{idevenement}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getIdevenement(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('evenementASC',name:'app_evenement_trier')]
    function listEvenementByName(EvenementRepository $evenementRepository){
        $evenements = $evenementRepository->createQueryBuilder('a')
        ->orderBy('a.nomevenement','ASC')
        ->getQuery()
        ->getResult();
        return $this->render('evenement/index.html.twig',
        ['evenements'=>$evenements]);
    }
}
