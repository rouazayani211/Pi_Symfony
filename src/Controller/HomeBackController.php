<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class HomeBackController extends AbstractController
{ 
    #[Route('/homeBack', name: 'app_home_back')]
    public function index(): Response
    {
        return $this->render('home_back/index.html.twig', [
            'controller_name' => 'HomeBackController',
        ]);
    }
}
