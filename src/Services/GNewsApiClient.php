<?php
namespace App\Service;
use Symfony\Component\HttpClient\HttpClient;

class GNewsApiClient
{
    private $apiKey;
    private $httpClient;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->httpClient = HttpClient::create();
    }

    public function fetchSoccerArticles(): array
    {
        $response = $this->httpClient->request('GET', 'https://gnews.io/api/v4/search?q=soccer&token=' . $this->apiKey);

        // Check if the request was successful
        if ($response->getStatusCode() !== 200) {
            // Handle error
            throw new \Exception('Failed to fetch articles from GNews API');
        }

        // Decode the JSON response
        $data = $response->toArray();

        // Extract the articles
        $articles = $data['articles'];

        return $articles;
    }
}

