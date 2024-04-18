<?php

namespace App\Controller;

use App\Form\CommandesType;
use App\Repository\CommandeRepository;
use App\Repository\CommandesRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class CommandesController extends AbstractController
{
    #[Route('/commandes', name: 'app_commandes')]
    public function index(): Response
    {
        return $this->render('commandes/index.html.twig', [
            'controller_name' => 'CommandesController',
        ]);
    }

    #[Route('/showCommand', name: 'showCommand')]
    public function showCommand(CommandesRepository $repo): Response
    {
        $commande=$repo->findAll() ;

        return $this->render('commandes/back/showCommand.html.twig', [
            'commande' => $commande
        ]);
    }

    #[Route('/editcommande/{id}', name: 'editcommande')]
    public function editcommande( $id,ManagerRegistry $manager, CommandesRepository $Repo, Request $request): Response
    {
        $em=$manager->getManager();
        $comds=$Repo->find($id);
       
        // Get the ID of the user
     
        $form=$this->createForm(CommandesType::class,$comds) ;
        $form->handleRequest($request) ; 
        if($form->isSubmitted() and $form->isValid()){      //est ce que button qrass aaliha wela et est ceque les camps hatt'hom valid wela 
 
            $em->persist($comds);                          //t'hadher requet INSERT
            $em->flush() ;                                   //execute 


            return $this->redirectToRoute('showCommand') ;
 
        }
 
        return $this->renderForm('commandes/back/editCommande.html.twig', [
           
            'f' => $form,
            
        ]);
    }


    #[Route('/deletecommande/{id}', name: 'deleteCommande')]
    public function deleteCommande($id, ManagerRegistry $manager, CommandesRepository $repo): Response
    {
        $emm = $manager->getManager();
        $idremove = $repo->find($id);
        $emm->remove($idremove);
        $emm->flush();
 
 
        return $this->redirectToRoute('showCommand');
    }
    
}
