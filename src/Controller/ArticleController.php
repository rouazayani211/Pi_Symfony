<?php
// src/Controller/ArticleController.php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;



class ArticleController extends AbstractController
{
    #[Route('/article/add', name: 'article_add')]
    public function addArticle(Request $request, ContainerBagInterface $params, SluggerInterface $slugger): Response
    {
        $article = new Articles();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imagePath */
            $imagePath = $form->get('imagePath')->getData(); // Assuming imageFile is the field name in your form

            if ($imagePath) {
                $originalFilename = pathinfo($imagePath->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename) . '-' . uniqid() . '.' . $imagePath->guessExtension();

                $uploadsDirectory = $params->get('uploads_directory'); // Get uploads directory from config
                $imageFullPath = $uploadsDirectory . '/images/' . $safeFilename; // Build full image path

                try {
                    $imagePath->move($uploadsDirectory . '/images', $safeFilename);
                    $article->setImagePath($safeFilename); // Set image path in entity
                } catch (IOExceptionInterface $e) {
                    // Handle upload error (e.g., display error message)
                    $this->addFlash('error', 'Error uploading image!');
                    return $this->redirectToRoute('article_add');
                }
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            // Redirect to a success page or back to the article list
            return $this->redirectToRoute('app_home_page');
        }

        return $this->render('articles/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/articles/delete', name: 'delete_article')]
    public function deleteArticle(ArticlesRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        return $this->render('articles/delete.html.twig', [
            'articles' => $articles,
        ]);
    }


    #[Route('/articles/delete/confirm', name: 'delete_article_confirm', methods: ['POST'])]
    public function confirmDeleteArticle(Request $request, ArticlesRepository $articleRepository, EntityManagerInterface $entityManager): Response
    {
        $articleId = $request->request->get('article');
        $article = $articleRepository->find($articleId);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('delete_article');
    }

    #[Route('/articles/select_to_modify', name: 'article_select_to_modify')]
    public function selectArticle(): Response
    {
    $articles = $this->getDoctrine()->getRepository(Articles::class)->findAll();

    return $this->render('articles/select_modify.html.twig', [
    'articles' => $articles,]);}

    

    #[Route('/articles/modify/{id}', name: 'article_modify')]
    public function modifyArticle(Request $request, Articles $article, ContainerBagInterface $params, SluggerInterface $slugger): Response
    {
    $form = $this->createForm(ArticleType::class, $article);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
    /** @var UploadedFile $imagePath */
    $imagePath = $form->get('imagePath')->getData(); // Assuming imageFile is the field name in your form

    if ($imagePath) {
    $originalFilename = pathinfo($imagePath->getClientOriginalName(), PATHINFO_FILENAME);
    $safeFilename = $slugger->slug($originalFilename) . '-' . uniqid() . '.' . $imagePath->guessExtension();

    $uploadsDirectory = $params->get('uploads_directory'); // Get uploads directory from config
    $imageFullPath = $uploadsDirectory . '/images/' . $safeFilename; // Build full image path

    try {
    $imagePath->move($uploadsDirectory . '/images', $safeFilename);
    $article->setImagePath($safeFilename); // Set image path in entity
    
    } catch (IOExceptionInterface $e) {
    // Handle upload error (e.g., display error message)
    $this->addFlash('error', 'Error uploading image!');
    return $this->redirectToRoute('article_select_to_modify');
    }
    }
    $this->getDoctrine()->getManager()->persist($article);
    $this->getDoctrine()->getManager()->flush();
    // Redirect after successful update (assuming you want to redirect)
    return $this->redirectToRoute('article_select_to_modify');
    } 
    else {
    // Render form on GET request
    return $this->render('articles/modify.html.twig', [
    'form' => $form->createView(),
    ]);
    }
    }

}

