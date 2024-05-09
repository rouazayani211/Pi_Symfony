<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\reservation1Type;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\Notifier\Notification\Notification;
use Joli\JoliNotif\Notification;
use Joli\JoliNotif\NotifierFactory;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\TicketRepository;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Endroid\QrCode\Color\Color;
use Endroid\QrCodeBundle\Response\QrCodeResponse;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\PdfWriter;
use Endroid\QrCode\Writer\Result\PdfResult;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Color\ColorInterface;
use Endroid\QrCode\Writer\Result\PngResult;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Margin\Margin;
use Endroid\QrCode\QrCode;



#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository,PaginatorInterface $paginator, Request $request): Response
    {
        $reservationQuery=$reservationRepository->findAll();
        // Paginer les résultats des reservation
        $reservation = $paginator->paginate(
            $reservationQuery, // Requête des résultats
            $request->query->getInt('page', 1), // Page actuelle
            4 // Nombre d'éléments par page
        );
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservation,
        ]);
    }
    #[Route('/back', name: 'app_reservation_indexback', methods: ['GET'])]
    public function indexback(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/indexback.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
      /*  $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);*/
        $reservation = new Reservation();
        $form = $this->createForm(reservation1Type::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();

            // Stockez l'ID de la réservation nouvellement créée dans la session
            $request->getSession()->set('new_reservation_id', $reservation->getId());

            // Redirigez l'utilisateur vers la création de tickets
            return $this->redirectToRoute('app_ticket_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/back/new', name: 'app_reservation_newback', methods: ['GET', 'POST'])]
    public function newback(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Traitement du formulaire si valide
            $entityManager->persist($reservation);
            $entityManager->flush();
        
            return $this->redirectToRoute('app_reservation_indexback', [], Response::HTTP_SEE_OTHER);
        }
        
        if ($form->isSubmitted() && !$form->isValid()) {
            // Affichage d'un avertissement seulement si le formulaire a été soumis
            $this->addFlash('warning', 'Il y a des erreurs dans le formulaire. Veuillez les corriger.');
        }


        return $this->renderForm('reservation/newback.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }
    #[Route('/back/{id}', name: 'app_reservation_showback', methods: ['GET'])]
    public function showback(Reservation $reservation): Response
    {
        return $this->render('reservation/showback.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }
    #[Route('/back/{id}/edit', name: 'app_reservation_editback', methods: ['GET', 'POST'])]
    public function editback(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
       /* $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            // Create a Notifier
            $notifier = NotifierFactory::create();
 
            // Create your notification
            $notification =(new Notification())
                ->setTitle('Notification title')
                ->setBody('This is the body of your notification');

            // Send it
            $notifier->send($notification);

            return $this->redirectToRoute('app_reservation_indexback', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/editback.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);*/
        $form = $this->createForm(ReservationType::class, $reservation);
         $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();
        
        // Create your notification
        $notifier = NotifierFactory::create();
        $notification = (new Notification())
            ->setTitle('Reservation Updated')
            ->setBody('Reservation ID: '.$reservation->getId().' - Status: '.$reservation->getStatutReservation().' - has been updated successfully.');
    
        // Envoyez la notification
        $notifier->send($notification);

        // Add success flash message
       // $flashBag->add('success', 'The reservation has been updated successfully.');

        // Redirect to indexback route
        return $this->redirectToRoute('app_reservation_indexback');
    }

    return $this->render('reservation/editback.html.twig', [
        'reservation' => $reservation,
        'form' => $form->createView(),
    ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/back/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function deleteback(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_indexback', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/generate-pdf/{id}', name: 'app_reservation_generate_pdf', requirements: ['id' => '\d+'])]
    public function generatePdf(Reservation $reservation): Response
    {
        // Créez une instance de Dompdf
        $dompdf = new Dompdf();

        // Récupérez le contenu HTML à partir d'un template Twig
        $html = $this->renderView('reservation/pdf.html.twig', [
            'reservation' => $reservation,
        ]);

        // Chargez le contenu HTML dans Dompdf
        $dompdf->loadHtml($html);

        // Réglez les options de Dompdf si nécessaire
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf->setOptions($options);

        // Rendre le PDF
        $dompdf->render();

        // Récupérer le contenu du PDF
        $pdfContent = $dompdf->output();

        // Créez une réponse avec le contenu PDF
        $response = new Response($pdfContent);

        // Configurez les en-têtes pour indiquer qu'il s'agit d'un fichier PDF à télécharger
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'inline; filename="reservation' . $reservation->getId() . '.pdf"');

        return $response;
    }

    #[Route('/generate-all-pdf', name: 'app_reservation_generate_all_pdf')]
    public function generateAllPdf(ReservationRepository $reservationRepository): Response
    {
        // Récupérer toutes les réservations disponibles
        $reservations = $reservationRepository->findAll();
    
        // Créer une instance de Dompdf
        $dompdf = new Dompdf();
    
        // Contenu HTML pour toutes les réservations
        $html = '';
        foreach ($reservations as $reservation) {
            // Récupérer le contenu HTML pour une réservation
            $reservationHtml = $this->renderView('reservation/pdfall.html.twig', [
                'reservation' => $reservation,
            ]);
    
            // Ajouter le contenu HTML de la réservation au contenu global
            $html .= $reservationHtml;
        }
    
        // Charger le contenu HTML dans Dompdf
        $dompdf->loadHtml($html);
    
        // Réglez les options de Dompdf si nécessaire
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf->setOptions($options);
    
        // Rendre le PDF
        $dompdf->render();
    
        // Récupérer le contenu du PDF
        $pdfContent = $dompdf->output();
    
        // Créer une réponse avec le contenu PDF
        $response = new Response($pdfContent);
    
        // Configurez les en-têtes pour indiquer qu'il s'agit d'un fichier PDF à télécharger
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'inline; filename="all_reservations.pdf"');
    
        return $response;
    }

    #[Route('/calendarApi', name: 'reservation_calendarApi')]
    public function calendarApi(ReservationRepository $reservationRepository): Response
    {
        // Récupérer les dates de réservation et d'événement depuis la base de données
        $reservations = $reservationRepository->findAll();
       // $eventDates = $ticketRepository->getAllEventDates();
            $rdvs =[];
            foreach($reservations as $reservation)
            {
                $dateDeReservation = $reservation->getDateDeReservation();
                $dateReservationFormatted = $dateDeReservation ? $dateDeReservation->format('Y-m-d') : null;
    
                $rdvs[] = [
                    "id"=>(int)$reservation->getId(),
                    "dateDeReservation"=>$dateReservationFormatted,
                    "nombreTicket"=>(int)$reservation->getNombreTicket(),
                    "statutReservation"=>$reservation->getStatutReservation(),
                    "idUser"=>(int)$reservation->getIdUser()
                ];
            }
        $data =json_encode($rdvs);
        return $this->render('reservation/calendar.html.twig', compact('data'));
    }

    #[Route('/reservation/afficherqrcode/{id}', name: 'app_reservation_afficher_qrcode', requirements: ['id' => '\d+'])]
    public function afficherQrCode(Reservation $reservation, BuilderInterface $qrCodeBuilder): Response
    {
        $qrCodeContent = json_encode([
            'idReservation' => $reservation->getId(),
            'idUser' => $reservation->getIdUser(),
            'Datedereservation' => $reservation->getDateDeReservation(),
            'StatutCommande' => $reservation->getStatutReservation(),
            'NombreTicket' => $reservation->getNombreTicket(),
        ]);

        $url = 'Les proprietes de la reservation sont ' . $qrCodeContent;
        $label = 'QR Code de la reservation ' . $reservation->getId();

        $qrCode = $qrCodeBuilder
            ->data($url)
            ->encoding(new Encoding('UTF-8'))
            ->size(400)
            ->margin(10)
            ->labelText($label)
            ->labelMargin(new Margin(15, 5, 5, 5))
            ->build();

        // Convert QR code to data URI format
        $qrCodeData = $qrCode->getDataUri();

        // Return the response with the QR code data URI
        return $this->render('reservation/afficher_qrcode_modal.html.twig', [
            'qrCodeData' => $qrCodeData,
            'reservation' => $reservation,
        ]);
    }

        
}