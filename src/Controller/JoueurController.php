<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Form\JoueurType;
use App\Repository\JoueurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Dompdf\Dompdf;
use Dompdf\Options;

use Symfony\Component\HttpClient\HttpClient;

use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

use Knp\Component\Pager\PaginatorInterface;


#[Route('/joueur')]
class JoueurController extends AbstractController
{
    #[Route('/front', name: 'app_joueur_front', methods: ['GET'])]
    public function front(JoueurRepository $joueurRepository): Response
    {
        return $this->render('joueurfront.html.twig', [
            'joueurs' => $joueurRepository->findAll(),
        ]);
    }
    #[Route('/front/show/{id}', name: 'app_joueur_show_front', methods: ['GET'])]
    public function showFront(Joueur $joueur): Response
    {
        return $this->render('joueurfront_show.html.twig', [
            'joueur' => $joueur,
        ]);
    }

    #[Route('/', name: 'app_joueur_index', methods: ['GET'])]
    public function index(
        Request $request,
        JoueurRepository $joueurRepository,
        PaginatorInterface $paginator
    ): Response {
        $query = $request->query->get('query'); // Get the search query from the request

        // Create a query builder and check if a query parameter is provided
        if ($query) {
            // If there's a query, create the builder with a filter
            $queryBuilder = $joueurRepository
                ->createQueryBuilder('j')
                ->where('j.nom LIKE :query OR j.prenom LIKE :query') // Search by 'nom' or 'prenom'
                ->setParameter('query', '%' . $query . '%');
        } else {
            // If no query, create a simple query builder
            $queryBuilder = $joueurRepository->createQueryBuilder('j');
        }

        // Get the final query from the query builder
        $joueurQuery = $queryBuilder->getQuery();

        // Use the paginator to paginate the query
        $pagination = $paginator->paginate(
            $joueurQuery,
            $request->query->getInt('page', 1), // Current page (default is 1)
            5 // Number of records per page
        );

        return $this->render('joueur/index.html.twig', [
            'pagination' => $pagination, // Pass the pagination object
            'currentQuery' => $query, // Pass the current search query to the template
        ]);
    }


    #[Route('/new', name: 'app_joueur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $joueur = new Joueur();
        $form = $this->createForm(JoueurType::class, $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($joueur);
            $entityManager->flush();

            return $this->redirectToRoute('app_joueur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('joueur/new.html.twig', [
            'joueur' => $joueur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_joueur_show', methods: ['GET'])]
    public function show(Joueur $joueur): Response
    {
        return $this->render('joueur/show.html.twig', [
            'joueur' => $joueur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_joueur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Joueur $joueur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JoueurType::class, $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_joueur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('joueur/edit.html.twig', [
            'joueur' => $joueur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_joueur_delete', methods: ['POST'])]
    public function delete(Request $request, Joueur $joueur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$joueur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($joueur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_joueur_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/download', name: 'app_joueur_download', methods: ['GET'])]
public function download(EntityManagerInterface $entityManager): Response
{
    $joueurRepository = $entityManager->getRepository(Joueur::class);
    $joueurs = $joueurRepository->findAll();

    $content = "Id\tNom\tPrenom\tAge\tNumero\tPosition\tIdEquipe\n";
    foreach ($joueurs as $joueur) {
        $content .= sprintf(
            "%d\t%s\t%s\t%d\t%d\t%s\t%d\n",
            $joueur->getId(),
            $joueur->getNom(),
            $joueur->getPrenom(),
            $joueur->getAge(),
            $joueur->getNumero(),
            $joueur->getPosition(),
            $joueur->getid_Equipe()
        );
    }

    $response = new Response($content);
    $response->headers->set('Content-Type', 'text/plain');
    $response->headers->set('Content-Disposition', 'attachment; filename="joueurs.txt"');

    return $response;
}

#[Route('/pdf/download', name: 'app_joueur_pdf_download', methods: ['GET'])]
public function downloadPdf(JoueurRepository $joueurRepository): Response
{
    $joueurs = $joueurRepository->findAll();

    // Generate the HTML content for the PDF
    $html = $this->renderView('joueur/pdf.html.twig', [
        'joueurs' => $joueurs,
    ]);

    // Configure Dompdf options
    $options = new Options();
    $options->set('isRemoteEnabled', true); // Enable fetching of remote assets, if needed
    $dompdf = new Dompdf($options);

    // Load HTML content and render as PDF
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait'); // Set paper size and orientation
    $dompdf->render();

    // Create a response with the PDF content
    $response = new Response($dompdf->output());
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment; filename="joueurs.pdf"');

    return $response;
}
#[Route('/joueur/summary/{nom}', name: 'app_joueur_summary', methods: ['GET'])]
public function summary(string $nom, JoueurRepository $joueurRepository): Response
{
    // Fetch the joueur entity to pass to the template
    $joueur = $joueurRepository->findOneBy(['nom' => $nom]);

    if (!$joueur) {
        throw $this->createNotFoundException('Joueur not found');
    }

    $httpClient = HttpClient::create();
    $apiUrl = 'https://en.wikipedia.org/api/rest_v1/page/summary/' . urlencode($nom);

    $response = $httpClient->request('GET', $apiUrl);

    if ($response->getStatusCode() !== 200) {
        throw $this->createNotFoundException('No summary found for ' . $nom);
    }

    $summaryData = $response->toArray();

    return $this->render('joueur/summary.html.twig', [
        'summary' => $summaryData,
        'joueur' => $joueur, // Pass the joueur entity to the template
    ]);
}

#[Route('/summaryfront/{nom}', name: 'app_joueur_summary_front', methods: ['GET'])]
public function summaryFront(string $nom, JoueurRepository $joueurRepository): Response
{
    // Find the Joueur by name
    $joueur = $joueurRepository->findOneBy(['nom' => $nom]);

    if (!$joueur) {
        throw $this->createNotFoundException('Joueur not found');
    }

    // Get summary from Wikipedia
    $httpClient = HttpClient::create();
    $apiUrl = 'https://en.wikipedia.org/api/rest_v1/page/summary/' . urlencode($nom);
    
    $response = $httpClient->request('GET', $apiUrl);
    
    if ($response->getStatusCode() !== 200) {
        throw $this->createNotFoundException('No summary found for ' . $nom);
    }
    
    $summaryData = $response->toArray();
    
    return $this->render('summaryfront.html.twig', [
        'summary' => $summaryData,
        'joueur' => $joueur,
    ]);
}

#[Route('/joueur/statistics', name: 'app_joueur_statistics', methods: ['GET'])]
    public function statistics(JoueurRepository $joueurRepository): Response
    {
        $joueurs = $joueurRepository->findAll();
        
        $youngCount = 0;
        $middleAgeCount = 0;
        $oldCount = 0;

        // Classify "Joueurs" into age groups
        foreach ($joueurs as $joueur) {
            $age = $joueur->getAge();
            if ($age >= 15 && $age <= 23) {
                $youngCount++;
            } elseif ($age >= 24 && $age <= 30) {
                $middleAgeCount++;
            } else {
                $oldCount++;
            }
        }

        return $this->render('joueur/statistics.html.twig', [
            'youngCount' => $youngCount,
            'middleAgeCount' => $middleAgeCount,
            'oldCount' => $oldCount,
        ]);
    }


}
