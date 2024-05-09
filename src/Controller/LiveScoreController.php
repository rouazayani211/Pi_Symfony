<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;



class LiveScoresController extends AbstractController
{
    private $client;
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }
    
    #[Route('/live_scores', name: 'live_scores')]
    public function index(): Response
    {
        $apiKey = 'q6k8tQWZ5Xid1qluEgzZQihJC33KSBmfML8VI7cRBsyNmDwpM12cDtuP8Qpn'; // Replace with your actual API key
        $apiUrl = 'https://api.sportmonks.com/v3/football/fixtures?api_token=' . $apiKey;
        //.'&filters=fixtureLeagues:320,1161'

        $client = new HttpClient();

        
            $response = $this->client->request('GET', $apiUrl);
            

            if ($response->getStatusCode() === 200) {
                $responseContent = json_decode($response->getContent(), true);
                $liveScores = $responseContent['data']; // Assuming data structure
                return $this->render('live_scores/index.html.twig', [
                    'liveScores' => $liveScores,
                ]);
            } else {
                // Handle API errors
                $errorMessage = 'SportsMonk API request failed with status code: ' . $response->getStatusCode();
                return $this->render('live_scores/error.html.twig', [
                    'errorMessage' => $errorMessage,
                ]);
            }
    }
}
