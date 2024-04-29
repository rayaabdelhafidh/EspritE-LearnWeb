<?php

namespace App\Controller;

use App\Entity\Emploi;
use App\Entity\EmploiMatiere;
use App\Form\EmploiType;
use App\Repository\EmploiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Flasher\Prime\FlasherInterface;
use DateTime;



#[Route('/emploi')]
class EmploiController extends AbstractController
{
  

    #[Route('/', name: 'app_emploi_index', methods: ['GET'])]
    public function index(EmploiRepository $emploiRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Get the selected premierdate and dernierdate from the request
        $selectedEmploiRange = $request->query->get('emploi');
    
        if ($selectedEmploiRange) {
            // Split the selected emploi range into premierdate and dernierdate
            [$premierdateStr, $dernierdateStr] = explode('-', $selectedEmploiRange);
    
            // Convert string representations back to DateTime objects
            $premierdate = DateTime::createFromFormat('Y-m-d', $premierdateStr);
            $dernierdate = DateTime::createFromFormat('Y-m-d', $dernierdateStr);
    
            // Output the selected date range for debugging
            dump($premierdate, $dernierdate);
    
            // Query emplois within the selected date range
            $queryBuilder = $emploiRepository->createQueryBuilder('e')
                ->where('e.premierdate >= :start_date')
                ->andWhere('e.dernierdate <= :end_date')
                ->setParameter('start_date', $premierdate)
                ->setParameter('end_date', $dernierdate);
    
            // Sort emplois by premierdate and dernierdate
            $queryBuilder->orderBy('e.premierdate', 'ASC')
                        ->addOrderBy('e.dernierdate', 'ASC');
    
            // Output the generated SQL query for debugging
            dump($queryBuilder->getQuery()->getSQL());
    
            // Paginate the query results
            $pagination = $paginator->paginate(
                $queryBuilder->getQuery(),
                $request->query->getInt('page', 1),
                5
            );
    
            return $this->render('emploi/index.html.twig', [
                'pagination' => $pagination,
            ]);
        }
    
        // If no emploi range is selected, render all emplois
        $queryBuilder = $emploiRepository->createQueryBuilder('e');
        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            5
        );
    
        return $this->render('emploi/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    

    


    #[Route('/new', name: 'app_emploi_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $emploi = new Emploi();
        $form = $this->createForm(EmploiType::class, $emploi);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($emploi);
            $entityManager->flush();

            $this->get('session')->set('new_empoi_data', $emploi);
    
            return $this->redirectToRoute('app_emploi_matiere_new', ['emploiId' => $emploi->getId()]);
        }
    
        return $this->render('emploi/new.html.twig', [
            'emploi' => $emploi,
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/{id}/edit', name: 'app_emploi_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Emploi $emploi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EmploiType::class, $emploi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_emploi_matiere_edit', ['emploiId' => $emploi->getId()]);
        }

        return $this->renderForm('emploi/edit.html.twig', [
            'emploi' => $emploi,
            'form' => $form,
        ]);
    }

    

    #[Route('/{id}', name: 'app_emploi_show', methods: ['GET'])]
    public function show(Emploi $emploi): Response
    {
        $EmploiMatiere = $this->getDoctrine()->getRepository(EmploiMatiere::class)->findOneBy(['emploi' => $emploi]);
        return $this->render('emploi/show.html.twig', [
            'emploi' => $emploi,
            'EmploiMatiere' => $EmploiMatiere
        ]);
    }

   
    #[Route('/{id}', name: 'app_emploi_delete', methods: ['POST'])]
public function delete(Request $request, Emploi $emploi, EntityManagerInterface $entityManager, FlasherInterface $flasher): Response
{
    if ($this->isCsrfTokenValid('delete'.$emploi->getId(), $request->request->get('_token'))) {
        $emploiMatieres = $entityManager->getRepository(EmploiMatiere::class)->findBy(['emploi' => $emploi]);

        foreach ($emploiMatieres as $emploiMatiere) {
            $entityManager->remove($emploiMatiere);
        }

        $entityManager->remove($emploi);
        $entityManager->flush();
        $flasher->addSuccess('emploi supprimé avec succès.');
    }

    return $this->redirectToRoute('app_emploi_index', [], Response::HTTP_SEE_OTHER);
}
}
