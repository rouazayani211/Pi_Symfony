<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeBController extends AbstractController
{
    /**
     * @Route("/Back", name="home")
     */
    public function index(): Response
    {
        return $this->render('/index.html.twig');
    }

    /**
    * @Route("/Front", name="front")
    */
    public function myPage(): Response
   {
    return $this->render('/base.html.twig');
   }

}
