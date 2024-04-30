<?php

namespace App\Controller;

use App\Services\EmailService;
use Symfony\Component\HttpFoundation\Request;
use Stripe ;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    #[Route('/stripe', name: 'stripe')]
    public function index(Request $request): Response
    {
        $montant = $request->getSession()->get('montant');

        if($montant== null){
          return  $this->redirectToRoute('showproduitfront');
        }

        return $this->render('stripe/index.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
            'montant' => $montant
        ]);
    }


    #[Route('/stripe/create-charge', name: 'stripe_charge')]
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
        $emailService->sendEmail("yosra.challekh@esprit.tn", "Confirm payment", "Your Payment has been successful, amount of " . $montant . " TND has been charged.");

        $session->remove('montant');

        return $this->redirectToRoute('stripe', [], Response::HTTP_SEE_OTHER);
    }
}
