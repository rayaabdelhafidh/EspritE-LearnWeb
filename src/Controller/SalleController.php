<?php

namespace App\Controller;

use App\Entity\Salle;
use App\Form\SalleType;
use App\Repository\SalleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Flasher\Prime\FlasherInterface;

#[Route('/salle')]
class SalleController extends AbstractController
{  
#[Route('/', name: 'app_salle_index', methods: ['GET'])]
public function index(SalleRepository $salleRepository, PaginatorInterface $paginator, Request $request): Response
{
    $query = $salleRepository->createQueryBuilder('s')
        ->getQuery();

    $pagination = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1), 
        7 
    );
    $pagination->setPageRange(1); 
    return $this->render('salle/index.html.twig', [
        'pagination' => $pagination,
    ]);
}

    
    #[Route('/new', name: 'app_salle_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FlasherInterface $flasher): Response
    {
        $salle = new Salle();
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($salle);
            $entityManager->flush();
        $flasher->addSuccess('Salle ajoutée avec succès.');

            return $this->redirectToRoute('app_salle_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('salle/new.html.twig', [
            'salle' => $salle,
            'form' => $form,
        ]);
    }

    #[Route('/{salleId}', name: 'app_salle_show', methods: ['GET'])]
    public function show(Salle $salle): Response
    {
        return $this->render('salle/show.html.twig', [
            'salle' => $salle,
        ]);
    }

    #[Route('/{salleId}/edit', name: 'app_salle_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Salle $salle, EntityManagerInterface $entityManager, FlasherInterface $flasher): Response
    {
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $flasher->addSuccess('Salle modifiée avec succès.');

            return $this->redirectToRoute('app_salle_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('salle/edit.html.twig', [
            'salle' => $salle,
            'form' => $form,
        ]);
    }
    

    #[Route('/{salleId}', name: 'app_salle_delete', methods: ['POST'])]
    public function delete(Request $request, Salle $salle, EntityManagerInterface $entityManager, FlasherInterface $flasher): Response
    {
        if ($this->isCsrfTokenValid('delete'.$salle->getSalleId(), $request->request->get('_token'))) {
            $entityManager->remove($salle);
            $entityManager->flush();
            $flasher->addSuccess('Salle supprimée avec succès.');
        } else {
            $flasher->addError('La suppression de la salle a échoué.');
        }

        return $this->redirectToRoute('app_salle_index', [], Response::HTTP_SEE_OTHER);
    }
}
