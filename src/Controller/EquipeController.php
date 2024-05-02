<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Form\EquipeType;
use App\Repository\EquipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

<<<<<<< HEAD
use Dompdf\Dompdf;
use Dompdf\Options;

use Knp\Component\Pager\PaginatorInterface;

use Symfony\Component\HttpClient\HttpClient;




=======
>>>>>>> c0a6901e20af18834aaf204d50cd2c2b74f48da8
#[Route('/equipe')]
class EquipeController extends AbstractController
{
    #[Route('/front', name: 'app_equipe_front', methods: ['GET'])]
<<<<<<< HEAD
    public function front(Request $request,EquipeRepository $equipeRepository): Response
    {
        $query = $request->query->get('query', '');
        $sortField = $request->query->get('sort', 'classement'); // Default sort field
        $sortDirection = $request->query->get('direction', 'asc'); // Default sort direction
    
        // If there's a search query, add filtering based on it
        $qb = $equipeRepository->createQueryBuilder('e');
        
        if ($query) {
            $qb->where('e.nom LIKE :search')
               ->setParameter('search', '%' . $query . '%');
        }
    
        // Add sorting logic based on the specified field and direction
        $qb->orderBy("e.$sortField", $sortDirection);
        
        // Get the results
        $equipes = $qb->getQuery()->getResult();
    
        return $this->render('equipefront.html.twig', [
            'equipes' => $equipes,
            'currentSortField' => $sortField,
            'currentSortDirection' => $sortDirection,
            'currentQuery' => $query,
        ]);
    }
    #[Route('/front/show/{idEquipe}', name: 'app_equipe_show_front', methods: ['GET'])]
    public function showFront(Equipe $equipe): Response
    {
        return $this->render('equipefront_show.html.twig', [
            'equipe' => $equipe,
        ]);
    }
    

    #[Route('/', name: 'app_equipe_index', methods: ['GET'])]
    public function index(
        Request $request,
        EquipeRepository $equipeRepository,
        PaginatorInterface $paginator
    ): Response {
        $query = $request->query->get('query'); // Search query

        // Build the query to fetch all Equipes
        $equipeQuery = $query
            ? $equipeRepository->createQueryBuilder('e')
                ->where('e.nom LIKE :query')
                ->setParameter('query', '%' . $query . '%')
                ->getQuery()
            : $equipeRepository->createQueryBuilder('e')
                ->getQuery();

        // Use the paginator to paginate the query
        $pagination = $paginator->paginate(
            $equipeQuery,
            $request->query->getInt('page', 1), // The current page number (default is 1)
            5 // Number of records per page
        );

        return $this->render('equipe/index.html.twig', [
            'pagination' => $pagination,
=======
    public function front(EquipeRepository $equipeRepository): Response
    {
        return $this->render('equipefront.html.twig', [
            'equipes' => $equipeRepository->findAll(),
        ]);
    }

    #[Route('/', name: 'app_equipe_index', methods: ['GET'])]
    public function index(EquipeRepository $equipeRepository): Response
    {
        return $this->render('equipe/index.html.twig', [
            'equipes' => $equipeRepository->findAll(),
>>>>>>> c0a6901e20af18834aaf204d50cd2c2b74f48da8
        ]);
    }

    #[Route('/new', name: 'app_equipe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $equipe = new Equipe();
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($equipe);
            $entityManager->flush();

            return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('equipe/new.html.twig', [
            'equipe' => $equipe,
            'form' => $form,
        ]);
    }

<<<<<<< HEAD
    #[Route('/equipe/{idEquipe}', name: 'app_equipe_show', methods: ['GET'])]
=======
    #[Route('/{idEquipe}', name: 'app_equipe_show', methods: ['GET'])]
>>>>>>> c0a6901e20af18834aaf204d50cd2c2b74f48da8
    public function show(Equipe $equipe): Response
    {
        return $this->render('equipe/show.html.twig', [
            'equipe' => $equipe,
        ]);
    }

    #[Route('/{idEquipe}/edit', name: 'app_equipe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Equipe $equipe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('equipe/edit.html.twig', [
            'equipe' => $equipe,
            'form' => $form,
        ]);
    }

    #[Route('/{idEquipe}', name: 'app_equipe_delete', methods: ['POST'])]
    public function delete(Request $request, Equipe $equipe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$equipe->getIdEquipe(), $request->request->get('_token'))) {
            $entityManager->remove($equipe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
    }
<<<<<<< HEAD

    #[Route('/download', name: 'app_equipe_download_pdf', methods: ['GET'])]
    public function downloadPdf(EquipeRepository $equipeRepository): Response
    {
        // Get the data to include in the PDF
        $equipes = $equipeRepository->findAll();
    
        // Initialize Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true); // Needed if you use external assets like images
        $dompdf = new Dompdf($options);
    
        // Create the PDF content
        $html = $this->renderView('equipe/pdf.html.twig', [
            'equipes' => $equipes,
        ]);
    
        // Load HTML and render
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait'); // Adjust the paper size and orientation
        $dompdf->render();
    
        // Return the PDF as a binary response with the correct headers
        $pdfOutput = $dompdf->output();
        $response = new Response($pdfOutput);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="equipes.pdf"');
    
        return $response;
    }

    #[Route('/download-text', name: 'app_equipe_download_text', methods: ['GET'])]
    public function downloadText(EquipeRepository $equipeRepository): Response
    {
        // Get the list of "Equipes"
        $equipes = $equipeRepository->findAll();

        // Build the text content
        $content = "IdEquipe\tNom\tRegion\tLigue\tClassement\n"; // Header row
        foreach ($equipes as $equipe) {
            $content .= sprintf(
                "%d\t%s\t%s\t%s\t%d\n",
                $equipe->getIdEquipe(),
                $equipe->getNom(),
                $equipe->getRegion(),
                $equipe->getLigue(),
                $equipe->getClassement()
            );
        }

        // Return the response with appropriate headers
        $response = new Response($content);
        $response->headers->set('Content-Type', 'text/plain'); // Set content type for text
        $response->headers->set('Content-Disposition', 'attachment; filename="equipes.txt"'); // Define the download filename

        return $response;
    }

    #[Route('/statistics', name: 'app_equipe_statistics', methods: ['GET'])]
    public function statistics(EquipeRepository $equipeRepository): Response
    {
        // Get all "Equipes"
        $equipes = $equipeRepository->findAll();

        // Initialize counts for each category
        $promotionCount = 0;
        $noRewardsCount = 0;
        $relegationCount = 0;

        // Categorize "Equipes" based on "classement"
        foreach ($equipes as $equipe) {
            $classement = $equipe->getClassement();

            if ($classement >= 1 && $classement <= 6) {
                $promotionCount++;
            } elseif ($classement >= 7 && $classement <= 16) {
                $noRewardsCount++;
            } else {
                // Consider 17-20 for relegation
                $relegationCount++;
            }
        }

        return $this->render('equipe/statistics.html.twig', [
            'promotionCount' => $promotionCount,
            'noRewardsCount' => $noRewardsCount,
            'relegationCount' => $relegationCount,
        ]);
    }

    
    const YOUTUBE_SEARCH_URL = "https://www.googleapis.com/youtube/v3/search";  // Class constant for YouTube search URL

    #[Route('/equipe/video/{equipeNom}', name: 'app_equipe_video', methods: ['GET'])]
    public function showVideo(Request $request, string $equipeNom, EquipeRepository $equipeRepository): Response
    {
        // Retrieve the Equipe entity
        $equipe = $equipeRepository->findOneBy(['nom' => $equipeNom]);
        
        if (!$equipe) {
            // If no Equipe found, return a not found response
            throw $this->createNotFoundException("Equipe with name '$equipeNom' not found.");
        }

        // Construct the search query and request URL
        $apiKey = "AIzaSyDxDaCWxRtl6uANa4qyDNgJO0BWF8-waKE";  // Replace with your API key
        $searchQuery = urlencode($equipeNom . " latest games highlights");
        $requestUrl = self::YOUTUBE_SEARCH_URL . "?part=snippet&maxResults=1&q=" . $searchQuery . "&key=" . $apiKey;

        try {
            // Use HttpClient to send the HTTP request
            $client = HttpClient::create();
            $httpResponse = $client->request('GET', $requestUrl);

            // Check for successful response
            if ($httpResponse->getStatusCode() !== 200) {
                throw new \Exception("Failed to fetch YouTube video. Status code: " . $httpResponse->getStatusCode());
            }

            // Parse the response to get the video ID
            $content = $httpResponse->getContent();
            $videoId = $this->parseVideoIdFromResponse($content);
            
            if ($videoId === null) {
                throw new \Exception("Failed to retrieve video ID from YouTube response.");
            }

            // Construct the YouTube embed URL
            $youtubeEmbedUrl = "https://www.youtube.com/embed/" . $videoId;

            // Render the video template, passing the embed URL and the Equipe entity
            return $this->render('equipe/video.html.twig', [
                'youtubeEmbedUrl' => $youtubeEmbedUrl,
                'equipe' => $equipe,
            ]);
        } catch (\Exception $e) {
            // Log the exception and display an error message
            $this->addFlash('error', "An error occurred while fetching video highlights: " . $e->getMessage());
            
            // Redirect to the Equipe's show page or display a friendly error message
            return $this->redirectToRoute('app_equipe_show', ['idEquipe' => $equipe->getIdEquipe()]);
        }
    }

    private function parseVideoIdFromResponse($content)
    {
        // Parse the JSON response to extract the video ID
        $data = json_decode($content, true);
        if (isset($data['items'][0]['id']['videoId'])) {
            return $data['items'][0]['id']['videoId'];
        }
        return null;
    }
    #[Route('/front/video/{equipeNom}', name: 'app_equipe_video_front', methods: ['GET'])]
    public function showVideofront(Request $request, string $equipeNom, EquipeRepository $equipeRepository): Response
    {
        // Retrieve the Equipe entity
        $equipe = $equipeRepository->findOneBy(['nom' => $equipeNom]);
        
        if (!$equipe) {
            // If no Equipe found, return a not found response
            throw $this->createNotFoundException("Equipe with name '$equipeNom' not found.");
        }

        // Construct the search query and request URL
        $apiKey = "AIzaSyDxDaCWxRtl6uANa4qyDNgJO0BWF8-waKE";  // Replace with your API key
        $searchQuery = urlencode($equipeNom . " latest games highlights");
        $requestUrl = self::YOUTUBE_SEARCH_URL . "?part=snippet&maxResults=1&q=" . $searchQuery . "&key=" . $apiKey;

        try {
            // Use HttpClient to send the HTTP request
            $client = HttpClient::create();
            $httpResponse = $client->request('GET', $requestUrl);

            // Check for successful response
            if ($httpResponse->getStatusCode() !== 200) {
                throw new \Exception("Failed to fetch YouTube video. Status code: " . $httpResponse->getStatusCode());
            }

            // Parse the response to get the video ID
            $content = $httpResponse->getContent();
            $videoId = $this->parseVideoIdFromResponse($content);
            
            if ($videoId === null) {
                throw new \Exception("Failed to retrieve video ID from YouTube response.");
            }

            // Construct the YouTube embed URL
            $youtubeEmbedUrl = "https://www.youtube.com/embed/" . $videoId;

            // Render the video template, passing the embed URL and the Equipe entity
            return $this->render('videofront.html.twig', [
                'youtubeEmbedUrl' => $youtubeEmbedUrl,
                'equipe' => $equipe,
            ]);
        } catch (\Exception $e) {
            // Log the exception and display an error message
            $this->addFlash('error', "An error occurred while fetching video highlights: " . $e->getMessage());
            
            // Redirect to the Equipe's show page or display a friendly error message
            return $this->redirectToRoute('app_equipe_show_front', ['idEquipe' => $equipe->getIdEquipe()]);
        }
    }

   

=======
>>>>>>> c0a6901e20af18834aaf204d50cd2c2b74f48da8
}
