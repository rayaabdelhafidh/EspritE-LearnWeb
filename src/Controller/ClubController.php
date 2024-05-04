<?php

namespace App\Controller;

use App\Entity\Club;
use App\Form\ClubType;
use App\Repository\ClubRepository;
use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\AbstractType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;



#[Route('/club')]
class ClubController extends AbstractController
{
    #[Route('/', name: 'app_club_index')]
    public function index(ClubRepository $clubRepository): Response
    {
        // On va chercher toutes les catégories
       $clubs = $clubRepository->findAll();
        return $this->render('club/index.html.twig',
            ['clubs'=>$clubs,           
            ]
        );
    }

    #[Route('/clubEtud', name: 'app_club_indexFront')]
    public function indexFront(ChartBuilderInterface $chartBuilder, Request $request,EvenementRepository $evenementRepository,ClubRepository $clubRepository,PaginatorInterface $paginator): Response
    {
        $clubs= $clubRepository->findAll();
        // Fetch the count of events grouped by club
     $clubStatistics = $evenementRepository->countByClub();
 
     // Prepare arrays to store club names and event counts
     $clubse = [];
     $eventCounts = [];
 
     // Process the club statistics data
     foreach ($clubStatistics as $clubStat) {
         // Extract club name and event count
         $clubName = $clubStat['clubName'];
         $eventCount = $clubStat['count'];
 
         // Add club name and event count to the respective arrays
         $clubse[] = $clubName;
         $eventCounts[] = $eventCount;
     }
 
     $pagination = $paginator->paginate(
        $clubs, // Query/Array
        $request->query->getInt('page', 1), // Page number
        3 // Items per page
    );

        return $this->render('club/indexFront.html.twig',
            ['clubs'=>$pagination,
            'clubse' => json_encode($clubse), // Pass club names to the template
         'eventCounts' => json_encode($eventCounts), // Pass event counts to the template
         ]
        );
    }

    #[Route('/{idclub}/details', name: 'app_club_details', methods: ['GET'])]
    public function showDetails($idclub,ClubRepository $clubRepository, EvenementRepository $evenementRepository): Response
    {
        $club=$clubRepository->find($idclub);
        $evenements = $evenementRepository->findByclub($idclub);
        return $this->render('club/showDetails.html.twig', [
            'club' => $club,
            'evenements' => $evenements,
        ]);
    }

    #[Route('/new', name: 'app_club_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $club = new Club();
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($club);
            $entityManager->flush();

            return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('club/new.html.twig', [
            'club' => $club,
            'form' => $form,
        ]);
    }

    #[Route('/{idclub}', name: 'app_club_show', methods: ['GET'])]
    public function show(Club $club): Response
    {
        return $this->render('club/show.html.twig', [
            'club' => $club,
        ]);
    }

    #[Route('/{idclub}/edit', name: 'app_club_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Club $club, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('club/edit.html.twig', [
            'club' => $club,
            'form' => $form,
        ]);
    }

    #[Route('/{idclub}', name: 'app_club_delete', methods: ['POST'])]
    public function delete(Request $request, Club $club, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$club->getIdclub(), $request->request->get('_token'))) {
            $entityManager->remove($club);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('clubASC',name:'app_club_trier')]
    function listClubByName(ClubRepository $clubRepository){
        $clubs = $clubRepository->createQueryBuilder('a')
        ->orderBy('a.nomclub','ASC')
        ->getQuery()
        ->getResult();
        return $this->render('club/index.html.twig',
        ['clubs'=>$clubs]);
    }
    
    
    
}
