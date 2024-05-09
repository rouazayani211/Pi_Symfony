<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SoccerAPIController extends AbstractController
{
    private $client;
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }
    #[Route('/live_scores2', name: 'live_scores2')]
    
    public function index(): Response
    {
        $apiKey = '8ca40a419b40d463eee6cc621c167871'; // Replace with your actual API key
        $apiUrl = 'https://api.soccersapi.com/v2.2/fixtures/?user=safwanwerghi&token=8ca40a419b40d463eee6cc621c167871&t=schedule&d=2023-05-04';

        $client = new HttpClient();
        
            $response = $this->client->request('GET', $apiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Accept' => 'application/json',
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $responseContent = json_decode($response->getContent(), true);
                $liveScores = $responseContent['data'];

                return $this->render('live_scores/index.html.twig', [
                    'liveScores' => $liveScores,
                ]);
            } else {
                // Handle API errors (e.g., display an error message)
                $errorMessage = 'API request failed with status code: ' . $response->getStatusCode();
                return $this->render('live_scores/error.html.twig', [
                    'errorMessage' => $errorMessage,
                ]);
            }
        }
    }

