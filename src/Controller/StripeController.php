<?php

namespace App\Controller;
use Stripe\StripeClient;

use App\Services\EmailService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Exception;

class StripeController extends AbstractController
{

    private $manager;

    private $gateway;


    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager=$manager;

        $this->gateway= new StripeClient($_ENV['STRIPE_SECRET']);
    }
    


    #[Route('/stripe', name: 'stripe')]
    public function index(Request $request): Response
    {
        $montant = $request->getSession()->get('montant');

        if($montant== null){
          return  $this->redirectToRoute('showproduitfront');
        }

        return $this->checkout($request, $montant);
    }


    /*#[Route('/stripe/create-charge', name: 'stripe_charge')]
    public function createCharge(Request $request, SessionInterface $session ,EmailService $emailService)
    {
        $montant = $request->getSession()->get('montant');
        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        Stripe\Charge::create ([
            "amount" => $montant *100 ,
            "currency" => "usd",
            "source" => $request->request->get('stripeToken'),
            "description" => "Binaryboxtuts Payment Test"
        ]);
        $this->addFlash(
            'success',
            'Payment Successful!'
        );
        $emailService->sendEmail("aziz60602011@live.com", "Confirm payment", "Your Payment has been successful, amount of " . $montant . " TND has been charged.");

        $session->remove('montant');

        return $this->redirectToRoute('stripe', [], Response::HTTP_SEE_OTHER);
    }*/

    #[Route('/checkout', name: 'app_checkout', methods: ['POST'])]
    public function checkout(Request $request, $montant): Response
    {
        // Retrieve other required parameters
        $quantity = 1; // Default quantity for now
    
        try {
            // Créer le checkout (Create the checkout session)
            $checkout = $this->gateway->checkout->sessions->create([
                'line_items' => [[
                    'price_data' => [
                        'currency' => $_ENV['STRIPE_CURRENCY'],
                        'product_data' => [
                            'name' => 'Ballon',
                        ],
                        'unit_amount' => intval($montant * 100),
                    ],
                    'quantity' => $quantity,
                ]],
                'mode' => 'payment',
                'success_url' => $this->generateUrl('stripe_success', [], UrlGeneratorInterface::ABSOLUTE_URL), // Redirect URL on success
            ]);
    
            // Redirect user to Stripe checkout page
            return $this->redirect($checkout->url);
        } catch (Exception $e) {
            $this->addFlash('error', 'Payment Error: ' . $e->getMessage());
            return $this->redirectToRoute('stripe', [], Response::HTTP_SEE_OTHER);
        }
    }
    #[Route('/stripe/success', name: 'stripe_success')]
    public function success(Request $request, EmailService $emailService,)
    {
        $montant = $request->getSession()->get('montant');

        $sessionId = $request->query->get('session_id');
    
        try {
            $checkoutSession = $this->gateway->checkout->sessions->retrieve($sessionId);
           
                // Payment successful, proceed with email sending
                $emailService->sendEmail("yosra.challekh@esprit.tn", "Confirm payment", "Your Payment has been successful, amount of " . $montant . " TND has been charged.");
                $this->addFlash('success', 'Payment Successful!');
                return $this->redirectToRoute('showproduitfront');

           
        } catch (Exception $e) {
            $this->addFlash('error', 'Payment Error: ' . $e->getMessage());
        }
    
        return $this->redirectToRoute('showproduitfront', [], Response::HTTP_SEE_OTHER);
    }
    

}
