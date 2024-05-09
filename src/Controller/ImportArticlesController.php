<?php
// src/Controller/ImportArticlesController.php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Journalists;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\GNewsApiClient;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class ImportArticlesController extends AbstractController
{
    #[Route('/importarticles', name: 'import_articles')]
    public function importArticles(ContainerBagInterface $params, SluggerInterface $slugger): Response
    {
        // Fetch articles from the external API 
        $gNewsApiClient = new GNewsApiClient('b4732317906f154e594a4b552e49c125');
        $articlesFromApi = $gNewsApiClient->fetchSoccerArticles();
        //var_dump($articlesFromApi);
        
        $entityManager = $this->getDoctrine()->getManager();

        foreach ($articlesFromApi as $articleData) {
            // Extract article data from the API response
            $articleTitle = $articleData['title'];
            $articleContent = $articleData['content'];
            $articleCreationDate = new \DateTime($articleData['publishedAt']);
            $articleImageUrl = $articleData['image'];

            // Download the image from the URL
            $httpClient = HttpClient::create();
            $response = $httpClient->request('GET', $articleImageUrl);
            //$imageContent = $response->getContent();

            // Generate a unique filename for the image
            //$imageFileName = md5(uniqid()) . '.' . pathinfo($articleImageUrl, PATHINFO_EXTENSION);
            //$uploadsDirectory = $params->get('uploads_directory'); // Get uploads directory from config
            // Store the image in the local directory
            //$imagePath = $uploadsDirectory . '/images/' . $imageFileName;
            //file_put_contents($imagePath, $imageContent);
            //$imageContent->move($uploadsDirectory . '/images', $imagePath);
            

            // Check if the journalist exists in the database
            $journalistName = $articleData['source']['name'];
            $journalistUrl = $articleData['source']['url'];
            $journalist = $entityManager->getRepository(Journalists::class)->findOneByName($journalistName);

            // If the journalist doesn't exist, create a new one
            if (!$journalist) {
                $journalist = new Journalists();
                $journalist->setName($journalistName);
                $journalist->setEducation("None");
                $journalist->setIndependent(true);
                $journalist->setCurrentCompany($journalistUrl);
                // Set other properties for the journalist if needed
                $entityManager->persist($journalist);
            }

            // Create a new article entity
            $article = new Articles();
            $article->setTitle($articleTitle);
            $article->setContent($articleContent);
            $article->setCreationDate($articleCreationDate);
            $article->setImagePath($articleImageUrl); // Store the filename in the imagePath field
            $article->setJournalist($journalist);

            // Persist the article entity
            $entityManager->persist($article);
        }

        // Flush changes to the database
        $entityManager->flush();

        // Return a response (you can customize this)
        return $this->redirectToRoute('app_home_page');
    }
}
