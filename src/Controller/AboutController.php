<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController
{
    #[Route('/about', name: 'app_about')]
    public function index(): Response
    {
        return $this->render('about/index.html.twig', [
            'controller_name' => 'AboutController',
        ]);
    }
    #[Route('/aboutt', name: 'app_about')]
    public function checkGD(): Response
    {
        if (function_exists('gd_info')) {
            return new Response("Le module GD est activé.");
        } else {
            return new Response("Le module GD n'est pas activé.");
        }
    }
}
