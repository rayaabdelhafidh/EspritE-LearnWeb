<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourController extends AbstractController
{
    #[Route('/cour', name: 'app_cour')]
    public function index(): Response
    {
        return $this->render('cour/index.html.twig', [
            'controller_name' => 'CourController',
        ]);
    }
}
