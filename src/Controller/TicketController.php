<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\Reservation;
use App\Form\TicketType;
use App\Form\ticket1Type;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Joli\JoliNotif\Notification;
use Joli\JoliNotif\NotifierFactory;
use Hexaequo\CurrencyConverterBundle\Converter;


#[Route('/ticket')]
class TicketController extends AbstractController
{
    #[Route('/', name: 'app_ticket_index', methods: ['GET'])]
    public function index(TicketRepository $ticketRepository): Response
    {
        return $this->render('ticket/index.html.twig', [
            'tickets' => $ticketRepository->findAll(),
        ]);
    }
    #[Route('/back', name: 'app_ticket_indexback', methods: ['GET'])]
    public function indexback(TicketRepository $ticketRepository): Response
    {
        return $this->render('ticket/indexback.html.twig', [
            'tickets' => $ticketRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ticket_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservationId = $request->getSession()->get('new_reservation_id');

    if ($reservationId) {
        $entityManager = $this->getDoctrine()->getManager();

        $reservation = $entityManager->getRepository(Reservation::class)->find($reservationId);

        if (!$reservation) {
            throw $this->createNotFoundException('La réservation avec l\'ID '.$reservationId.' n\'existe pas.');
        }

        $ticket = new Ticket();
        $ticket->setIdReservation($reservation);

        // Removed: Don't set dateEvenement from reservation

        $form = $this->createForm(ticket1Type::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ticket);
            $entityManager->flush();

            return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
        }

        
        } 
        return $this->renderForm('ticket/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }
    #[Route('/back/new', name: 'app_ticket_newback', methods: ['GET', 'POST'])]
    public function newback(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ticket);
            $entityManager->flush();

            return $this->redirectToRoute('app_ticket_indexback', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ticket/newback.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ticket_show', methods: ['GET'])]
    public function show(Ticket $ticket): Response
    {
        return $this->render('ticket/show.html.twig', [
            'ticket' => $ticket,
        ]);
    }
    #[Route('/back/{id}', name: 'app_ticket_showback', methods: ['GET'])]
    public function showback(Ticket $ticket): Response
    {
        return $this->render('ticket/showback.html.twig', [
            'ticket' => $ticket,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ticket_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ticket $ticket, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ticket/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }
    #[Route('/back/{id}/edit', name: 'app_ticket_editback', methods: ['GET', 'POST'])]
    public function editback(Request $request, Ticket $ticket, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $notifier = NotifierFactory::create();
            $notification = (new Notification())
                ->setTitle('Ticket Updated')
                ->setBody('Ticket ID: '.$ticket->getId().' - Status: '.$ticket->getStatutTicket().' - has been updated successfully.');
        
            // Envoyez la notification
            $notifier->send($notification);
            return $this->redirectToRoute('app_ticket_indexback', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ticket/editback.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ticket_delete', methods: ['POST'])]
    public function delete(Request $request, Ticket $ticket, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ticket->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ticket);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/back/{id}', name: 'app_ticket_deleteback', methods: ['POST'])]
    public function deleteback(Request $request, Ticket $ticket, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ticket->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ticket);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ticket_indexback', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/ticket/{id}', name: 'ticket_details')]
    public function myPage(int $id, Converter $converter): Response
    {
        // Récupérez l'événement à partir de la base de données en utilisant son nom
        $ticket = $this->getDoctrine()->getRepository(Ticket::class)->findOneBy(['id' => $id]);

        // Vérifiez si l'événement existe
        if (!$ticket) {
            throw $this->createNotFoundException('La ticket n\'existe pas.');
        }

        // Récupérez le prix de l'événement en TND
        $totalPrix = $ticket->getPrix();

        // Convertir le prix en euros
        $prixEnEuros = $converter->convert($totalPrix, 'TND', 'EUR');
        // Convertir le prix en dollars
        $prixEnDollars = $converter->convert($totalPrix, 'TND', 'USD');
        // Convertir le prix en yuan
        $prixEnYuan = $converter->convert($totalPrix, 'TND', 'CNY');

        // Passez les prix convertis à la vue Twig pour affichage
        return $this->render('ticket/my_page.html.twig', [
            'ticket' => $ticket,
            'prixEnEuros' => $prixEnEuros,
            'prixEnDollars' => $prixEnDollars,
            'prixEnYuan' => $prixEnYuan,
        ]);
    } 
}
