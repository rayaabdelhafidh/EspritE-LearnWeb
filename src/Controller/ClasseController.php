<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Form\ClasseType;
use App\Repository\ClasseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Serializer\Encoder\JsonEncode;

#[Route('/admin/classe')]
class ClasseController extends AbstractController
{   
   

    #[Route('/', name: 'app_classe_index', methods: ['GET'])]
    public function index(ClasseRepository $classeRepository,Request $request,PaginatorInterface $paginator): Response
    {

        $classes = [];
        $classes = $classeRepository->findAll() ; 
        $paginatorclass= $paginator->paginate(
            $classes,  //notre data
            $request->query->getInt('page',1), //numero de la page en cour : par defaut 1
            4 //nombre des elements
        );
        return $this->render('classe/index.html.twig', [
            'classes' => $paginatorclass,
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
            $listYears[] = $class->getYear();
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


       /**
 * @Route("/tri/eve", name="evenement_tri")
 */
public function Tri(Request $request, ClasseRepository $repository, PaginatorInterface $paginator): Response
{
    // Retrieve the sorting order from the request
    $order = $request->get('type');

    // Perform sorting based on the order
    if ($order == "Croissant") {
        $classes = $repository->tri_asc();
    } else {
        $classes = $repository->tri_desc();
    }

    // Paginate the sorted results
    $paginatorclass = $paginator->paginate(
        $classes,  // Our data
        $request->query->getInt('page', 1), // Current page number, default is 1
        4 // Number of elements per page
    );

    // Render the twig view
    return $this->render('classe/index.html.twig', [
        'classes' => $paginatorclass
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
            flash()->addSuccess('classe ajoutée avec succés');

            return $this->redirectToRoute('app_classe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('classe/new.html.twig', [
            'classe' => $classe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_classe_show', methods: ['GET'])]
    public function show(Classe $classe): Response
    {
        return $this->render('classe/show.html.twig', [
            'classe' => $classe,
        ]);
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
    

    #[Route('/{id}/edit', name: 'app_classe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Classe $classe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClasseType::class, $classe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            flash()->addSuccess('classe modifiée avec succés');

            return $this->redirectToRoute('app_classe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('classe/edit.html.twig', [
            'classe' => $classe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_classe_delete', methods: ['POST'])]
    public function delete(Request $request, Classe $classe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$classe->getId(), $request->request->get('_token'))) {
            $entityManager->remove($classe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_classe_index', [], Response::HTTP_SEE_OTHER);
    }
}
