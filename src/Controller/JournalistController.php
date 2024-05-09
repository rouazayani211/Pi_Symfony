<?php
// src/Controller/JournalistController.php

namespace App\Controller;

use App\Entity\Journalists;
use App\Form\JournalistType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class JournalistController extends AbstractController
{
    #[Route('/journalists/add', name: 'journalist_add')]
    public function add(Request $request): Response
    {
        $journalist = new Journalists();
        $form = $this->createForm(JournalistType::class, $journalist);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($journalist);
            $entityManager->flush();

            return $this->redirectToRoute('journalists_list');
        }

        return $this->render('journalists/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/journalists', name: 'journalists_list')]
    public function list(): Response
    {
        $journalists = $this->getDoctrine()->getRepository(Journalists::class)->findAll();

        return $this->render('journalists/list.html.twig', [
            'journalists' => $journalists,
        ]);
    }

    #[Route('/modify-journalist/{id}', name: 'modify_journalist_form')]
    public function modifyJournalistForm(Request $request, Journalists $journalist): Response
    {
        $form = $this->createForm(JournalistType::class, $journalist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($journalist);
            $entityManager->flush();

            return $this->redirectToRoute('modify_journalist_select');
        }

        return $this->render('journalists/modify.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/modify-journalist', name: 'modify_journalist_select')]
    public function selectJournalist(Request $request): Response
    {
        // Fetch list of journalists from the database
        $journalists = $this->getDoctrine()->getRepository(Journalists::class)->findAll();

        return $this->render('journalists/select.html.twig', [
            'journalists' => $journalists,
        ]);
    }

    #[Route('/delete-journalist', name: 'delete_journalist_select')]
    public function selectJournalistToDelete(Request $request): Response
    {
        $journalists = $this->getDoctrine()->getRepository(Journalists::class)->findAll();

        return $this->render('journalists/select_to_delete.html.twig', [
            'journalists' => $journalists,
        ]);
    }



    #[Route('/confirm-delete-journalist/{id}', name: 'delete_journalist_confirm')]
    public function confirmDeleteJournalist(Journalists $journalist): Response
    {
        
        return $this->render('journalists/delete.html.twig', [
            'journalist' => $journalist,
        ]);
    }



    #[Route('/delete-journalist/{id}', name: 'delete_journalist')]
    public function deleteJournalist(Request $request, Journalists $journalist): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($journalist);
        $entityManager->flush();

        $this->addFlash('success', 'Journalist deleted successfully.');

        return $this->redirectToRoute('journalists_list');
    }

    
}
