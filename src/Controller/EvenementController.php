<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use App\Repository\ClubRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


#[Route('/evenement')]
class EvenementController extends AbstractController
{
    #[Route('/', name: 'app_evenement_index', methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository,PaginatorInterface $paginator): Response
    {
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
        ]);
    }

    #[Route('/evenementEtud', name: 'app_evenement_indexFront', methods: ['GET'])]
    public function indexFront(ChartBuilderInterface $chartBuilder, Request $request,EvenementRepository $evenementRepository,PaginatorInterface $paginator): Response
    {
    // Fetch all events (assuming you want to display them in the frontend as well) using pagination
     $events = $paginator->paginate(
         $evenementRepository->findAll(), // Fetch all events
         $request->query->getInt('page', 1),
         3
     );
 
     return $this->render('evenement/indexFront.html.twig', [
         'evenements' => $events,  // Pass the paginated events to the template
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

    #[Route('/{idevenement}/evenementpayment', name: 'app_evenement_payment')]
    public function payment(Evenement $evenement): Response
    {
    // Configure Stripe with your API key
    Stripe::setApiKey($this->getParameter('stripe_secret_key'));

    // Create a PaymentIntent with the price of the event
    $intent = PaymentIntent::create([
        'amount' => $evenement->getPrixEvenement() * 100, // Convertir en centimes
        'currency' => 'eur', // Devise
    ]);

    $this->addFlash('success', 'Succés de payment!');
    $this->addFlash('error', 'Payment refusé!');

    return $this->render('evenement/payment.html.twig', [
        'client_secret' => $intent->client_secret,
        'evenement' => $evenement, // Passer l'objet Evenement à la vue pour référence
        'stripe_public_key' => $this->getParameter('stripe_public_key'), // Passer la clé publique de Stripe au modèle
    ]);
}


#[Route('evenementStat',name:'app_evenement_stat')]
function statistique(ChartBuilderInterface $chartBuilder,EvenementRepository $evenementRepository,ClubRepository $clubRepository): Response
{
        // On va chercher le nombre d'annonces publiées par date
        $evenements = $evenementRepository->countByDate();

        $dates = [];
        $eventcount = [];

        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach($evenements as $evenement){
            $dates[] = $evenement['dateevenement'];
            $eventcount[] = $evenement['count'];
        }

        return $this->render('evenement/indexFront.html.twig', [
            'dates' => json_encode($dates),
            'eventcount' => json_encode($eventcount),
        ]);
    }

    /////////////////////////MAP///////////////////////
    private function generateGoogleMapLink(string $location): string
{
    // Formater le lieu pour qu'il soit compatible avec l'URL de la page show_map_by_location
    $formattedLocation = urlencode($location);
    
    // Retourner le nom de la route avec le paramètre lieu
    return 'show_map_by_location?lieu=' . $formattedLocation;
}


    #[Route('/show-map', name: 'show_map_by_location')]
    public function showMapByLocation(Request $request): Response
    {
        // Récupérer le lieu depuis la requête
        $lieu = $request->query->get('lieu');
        
        // Passer le lieu à la vue
        return $this->render('evenement/showDetails.html.twig', [
            'lieu' => $lieu,
        ]);
    }
}

