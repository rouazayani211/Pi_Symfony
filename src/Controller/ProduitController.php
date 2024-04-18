<?php

namespace App\Controller;

use App\Entity\Commandes;
use App\Entity\Produit;
use App\Form\CommandesType;
use App\Form\ProduitType;
use App\Repository\CommandesRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    #[Route('/', name: 'showproduit')]
    public function showproduit(ProduitRepository $repo ,Request $request): Response
    {
        $produits=$repo->findAll() ;

        return $this->render('produit/back/showproduit.html.twig', [
            'produit' => $produits
        ]);
    }

    #[Route('/addformproduit', name: 'addformproduit')]
    public function addformproduit(ManagerRegistry $managerRegistry ,Request $request): Response
    {   
        $em=$managerRegistry->getManager() ;             //donner l'acces au fonctionalite (persiste/flush)
 
        $produits =new Produit();
        $form=$this->createForm(ProduitType::class , $produits)   ;     //bch nasn3ou form fiha deux parametre li hiya lform w l'instance mteena
        $form->handleRequest($request) ;               //les donnes de requete http sont associé au formulaire 
 
        if($form->isSubmitted() and $form->isValid()){      //est ce que button qrass aaliha wela et est ceque les champs hatt'hom valid wela 
 
            $em->persist($produits);                          //t'hadher requet INSERT
            $em->flush() ;                                   //execute 
            return $this->redirectToRoute('showproduit') ;   //lehna nhott esm route mch rouute (name)
 
        }
        return $this->renderForm('produit/back/addformproduit.html.twig', [
            'f' => $form
        ]);
    }


    #[Route('/editformproduit/{id}', name: 'editformproduit')]
    public function editformproduit( $id,ManagerRegistry $manager, ProduitRepository $Repo, Request $request): Response
    {
        $em=$manager->getManager();
        $produits=$Repo->find($id);
       
        // Get the ID of the user
     
        $form=$this->createForm(ProduitType::class,$produits) ;
        $form->handleRequest($request) ; 
        if($form->isSubmitted() and $form->isValid()){      //est ce que button qrass aaliha wela et est ceque les camps hatt'hom valid wela 
 
            $em->persist($produits);                          //t'hadher requet INSERT
            $em->flush() ;                                   //execute 


            return $this->redirectToRoute('showproduit') ;
 
        }
 
        return $this->renderForm('produit/back/editformproduit.html.twig', [
           
            'f' => $form,
            
        ]);
    }
    #[Route('/deleteproduit/{id}', name: 'deleteproduit')]
    public function deleteproduit($id, ManagerRegistry $manager, ProduitRepository $repo, CommandesRepository $commandesRepository): Response
    {
        $entityManager = $manager->getManager();
        $produit = $repo->find($id);
    
        if (!$produit) {
            throw $this->createNotFoundException('Produit not found.');
        }
    
        // Supprimer les commandes associées au produit
        $commandes = $commandesRepository->findBy(['produit' => $produit]);
        foreach ($commandes as $commande) {
            $entityManager->remove($commande);
        }
    
        // Supprimer le produit lui-même
        $entityManager->remove($produit);
        $entityManager->flush();
    
        return $this->redirectToRoute('showproduit');
    }



    ///////////////////////////////////             Front           ********************************************


        public function render(string $view, array $parameters = [], ?Response $response = null): Response
    {
        // Retrieve the cart count from the session
        $cart = $this->get('session')->get('cart', []);
        $cartCount = count($cart);

        // Pass the cart count to all templates
        $parameters['cartCount'] = $cartCount;

        return parent::render($view, $parameters, $response);
    }

    #[Route('/showproduitfront', name: 'showproduitfront')]
public function showproduitfront(ProduitRepository $repo, Request $request): Response
{
    // Retrieve the list of products
    $produits = $repo->findAll();

    // Retrieve the cart from the session and get the cart count
    $cart = $request->getSession()->get('cart', []);
    $cartCount = count($cart);

    // Pass the list of products and cart count to the Twig template
    return $this->render('produit/front/showproduitfront.html.twig', [
        'produit' => $produits,
        'cartCount' => $cartCount, // Pass the cart count to the template
    ]);
}

#[Route('/add-to-cart/{id}', name: 'add_to_cart')]
public function addToCart(Request $request, $id): Response
{
    $produit = $this->getDoctrine()->getRepository(Produit::class)->find($id);

    if (!$produit) {
       throw $this->createNotFoundException('Produit not found.');
    }

    // Get the current cart from the session
    $cart = $request->getSession()->get('cart', []);

    // Ensure the 'quantity' key is set for the cart item
    if (!isset($cart[$id])) {
        $cart[$id] = [
            'id' => $produit->getId(),
            'description' => $produit->getDescription(),
            'nom' => $produit->getNomProduit(),
            'stock_disponible' => $produit->getStockDisponible(),
            'prix' => $produit->getPrix(),
        ];
    }

    
    // Update the cart in the session
    $request->getSession()->set('cart', $cart);
    
    return $this->redirectToRoute('showproduitfront');    
}






    #[Route('/showpanier', name: 'showpanier')]
    public function showCart(Request $request): Response
    {
        // Retrieve the cart from the session
        $cart = $request->getSession()->get('cart', []);

        // Render the cart template with the cart data
        return $this->render('produit/front/panier.html.twig', [
            'cart' => $cart,
        ]);
    }
    #[Route('/remove-from-cart/{id}', name: 'remove_from_cart')]
    public function removeFromCart(Request $request, $id): Response
    {
        // Retrieve the cart from the session
        $cart = $request->getSession()->get('cart', []);

        // Remove the product from the cart
        unset($cart[$id]);

        // Save the updated cart back to the session
        $request->getSession()->set('cart', $cart);

        // Redirect back to the cart page
        return $this->redirectToRoute('showpanier');
    }
    

    
//     #[Route('/validate-order', name: 'validate_order', methods: ['POST'])]
// public function validateOrder(Request $request, SessionInterface $session): Response
// {
//     // Retrieve the cart items from the session
//     $cart = $session->get('cart', []);

//     // Get the entity manager
//     $entityManager = $this->getDoctrine()->getManager();

    
//     // Loop through each product in the cart
//     foreach ($cart as $itemId => $item) {
//         // Retrieve the Produit entity from the database based on its ID
//         $produit = $this->getDoctrine()->getRepository(Produit::class)->find($itemId);

//         if (!$produit) {
//             // Handle the case where the produit is not found
//             // You can throw an exception or handle it based on your application's logic
//             continue; // Skip to the next item
//         }

//         // Add the quantity of the current product to the total quantity

//         // Create a new Commande entity
//         $commande = new Commandes();

//         // Set the properties for the Commande entity
//         $commande->setProduit($produit);
//         $commande->setDate(new \DateTime());

//         // Persist the Commande entity
//         $entityManager->persist($commande);
//     }

//     // Set the total quantity as nbrCommandes for the Commande entity

//     // Flush the changes to the database
//     $entityManager->flush();

//     // Clear the cart after validation
//     $session->remove('cart');

//     // Redirect the user to a confirmation page
//     return $this->redirectToRoute('showpanier');
// }

    

#[Route('/validate-order', name: 'validate_order', methods: ['GET', 'POST'])]
public function validateOrder(Request $request, SessionInterface $session,ManagerRegistry $managerRegistry): Response
{
    $em=$managerRegistry->getManager() ;             //donner l'acces au fonctionalite (persiste/flush)
 
        $commande =new Commandes();
        $form=$this->createForm(CommandesType::class , $commande)   ;     //bch nasn3ou form fiha deux parametre li hiya lform w l'instance mteena
        $form->handleRequest($request) ;               //les donnes de requete http sont associé au formulaire 
 
        if($form->isSubmitted() and $form->isValid()){      //est ce que button qrass aaliha wela et est ceque les champs hatt'hom valid wela 
 
            $em->persist($commande);                          //t'hadher requet INSERT
            $em->flush() ;                                   //execute 
            return $this->redirectToRoute('showproduitfront') ;   //lehna nhott esm route mch rouute (name)
 
        }
        return $this->renderForm('produit/front/validation.html.twig', [
            'f' => $form
        ]);
}

    
    

    
    




}
