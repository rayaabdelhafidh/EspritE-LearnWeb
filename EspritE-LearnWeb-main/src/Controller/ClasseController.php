<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Form\ClasseType;
use App\Repository\ClasseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;



#[Route('/classe')]
class ClasseController extends AbstractController
{
    #[Route('/listec', name: 'app_classe_index', methods: ['GET'])]
    public function index(ClasseRepository $classeRepository): Response
    {
        return $this->render('classe/index.html.twig', [
            'classes' => $classeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/tri/eve", name="evenement_tri")
     */
    public function Tri(Request $request,ClasseRepository $repository): Response
    {
        // Retrieve the entity manager of Doctrine
        $order=$request->get('type');
        if($order== "Croissant"){
            $evenements = $repository->tri_asc();
        }
        else {
            $evenements = $repository->tri_desc();
        }
        
        // Render the twig view
        return $this->render('classe/index.html.twig', [
            'classes' => $evenements
        ]);
}

    /**
     * @Route("/evenement/ajax_search", name="ajax_search" ,methods={"GET"})
     * @param Request $request
     * @param ClasseRepository $EvenementRepository
     * @return Response
     */
    public function searchAction(Request $request,ClasseRepository $evenementRepository) : Response
    {
        // $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $evenements =$evenementRepository->SearchNom($requestString);
        if(!$evenements) {
            $result['evenements']['error'] = "evenements non trouvée ";
        } else {
            $result['evenements'] = $this->getRealEntities($evenements);
        }
        return new Response(json_encode($result));
    }
    public function getRealEntities($classes){
        foreach ($classes as $classe){
            $realEntities[$classe->getIdClasse()] = [$classe->getNomClasse(),$classe->getNbreetudi(),$classe->getFiliere(),$classe->getNiveaux()];

        }
        return $realEntities;
    }
    #[Route('/print', name: 'app_classe_print', methods: ['GET'])]
    public function print( ClasseRepository $matiererepository)
    {
    
        $result = $matiererepository->findAll();
        $pdfOptions = new Options();
    
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
    
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('classe/print.html.twig', [
            'classes' => $result
        ]);
    
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');
    
        // Render the HTML as PDF
        $dompdf->render();
        // Output the generated PDF as a response with Content-Type set to 'application/pdf'
        return new Response($dompdf->output(), Response::HTTP_OK, [
            'Content-Type' => 'listedesclasses/pdf',
        ]);
    
    }

    #[Route('/new', name: 'app_classe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        
        $classe = new Classe();
        $form = $this->createForm(ClasseType::class, $classe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($classe);
            $entityManager->flush();
            flash()->addSuccess('classe Added Successfully');

            return $this->redirectToRoute('app_classe_index', [], Response::HTTP_SEE_OTHER);
        }
      

        return $this->renderForm('classe/new.html.twig', [
            'classe' => $classe,
            'form' => $form,
        ]);
    }

    #[Route('/stat', name: 'app_classe_stat', methods: ['GET'])]
    public function classStat(ClasseRepository $classeRepository,Request $request): Response
    {
        $year = $request->get("select");
        $classes = [];
        $listYears =[];
        $listdata=[];
        $classes = $classeRepository->findAll() ; 
        foreach($classes as $class){
            $listYears[] = $class->getFiliere();
        }
        $listdata[]=count($classeRepository->findclasseswithyear($year , "TIC"));
        $listdata[]=count($classeRepository->findclasseswithyear($year , "GC"));
        $listdata[]=count($classeRepository->findclasseswithyear($year , "BUSINESS"));

        return $this->render('classe/stat.html.twig', [
            'years'=> array_unique($listYears),
            'data'=> json_encode($listdata),
            'year'=>$year
        ]);
    }


    #[Route('/{idClasse}', name: 'app_classe_show', methods: ['GET'])]
    public function show(Classe $classe): Response
    {
        return $this->render('classe/show.html.twig', [
            'classe' => $classe,
        ]);
    }



    #[Route('/{idClasse}/edit', name: 'app_classe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Classe $classe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClasseType::class, $classe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_classe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('classe/edit.html.twig', [
            'classe' => $classe,
            'form' => $form,
        ]);
    }

    #[Route('/{idClasse}', name: 'app_classe_delete', methods: ['POST'])]
    public function delete(Request $request, Classe $classe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$classe->getIdClasse(), $request->request->get('_token'))) {
            $entityManager->remove($classe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_classe_index', [], Response::HTTP_SEE_OTHER);
    }
}
