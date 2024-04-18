<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    #[Route('/readutilisateur', name: 'read_utilisateur')]
    public function read(UtilisateurRepository $repo ): Response
    {
        $utilisateur = $repo->findAll();
        return $this->render('utilisateur/read.html.twig', [
            'Utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/readFrontutilisateur', name: 'readFront_utilisateur')]
    public function readFront(UtilisateurRepository $repo ): Response
    {
        $utilisateur = $repo->findAll();
        return $this->render('utilisateur/readFront.html.twig', [
            'Utilisateur' => $utilisateur,
        ]);
    }



    #[Route('/pages-login.html', name: 'login_utilisateur')]
    public function login(UtilisateurRepository $repo ): Response
    {
        return new Response("login page");
    }



}
