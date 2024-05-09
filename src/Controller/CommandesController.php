<?php

namespace App\Controller;

use App\Entity\Commandes;
use App\Form\CommandesType;
use App\Repository\CommandesRepository;
use PhpOffice\PhpSpreadsheet ;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class CommandesController extends AbstractController
{
    #[Route('/commandes', name: 'app_commandes')]
    public function index(): Response
    {
        return $this->render('commandes/index.html.twig', [
            'controller_name' => 'CommandesController',
        ]);
    }

    #[Route('/showCommand', name: 'showCommand')]
    public function showCommand(CommandesRepository $repo): Response
    {
        $commande=$repo->findAll() ;

        return $this->render('commandes/back/showCommand.html.twig', [
            'commande' => $commande
        ]);
    }

    #[Route('/editcommande/{id}', name: 'editcommande')]
    public function editcommande( $id,ManagerRegistry $manager, CommandesRepository $Repo, Request $request): Response
    {
        $em=$manager->getManager();
        $comds=$Repo->find($id);
       
        
     
        $form=$this->createForm(CommandesType::class,$comds) ;
        $form->handleRequest($request) ; 
        if($form->isSubmitted() and $form->isValid()){      
 
            $em->persist($comds);                          
            $em->flush() ;


            return $this->redirectToRoute('showCommand') ;
 
        }
 
        return $this->renderForm('commandes/back/editCommande.html.twig', [
           
            'f' => $form,
            
        ]);
    }


    #[Route('/deletecommande/{id}', name: 'deleteCommande')]
    public function deleteCommande($id, ManagerRegistry $manager, CommandesRepository $repo): Response
    {
        $emm = $manager->getManager();
        $idremove = $repo->find($id);
        $emm->remove($idremove);
        $emm->flush();
 
 
        return $this->redirectToRoute('showCommand');
    }
    


    #[Route('/export/excelcommande', name: 'app_event_exportcommande_excel', methods: ['GET'])]
    public function exportExcel(EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données des pays depuis la base de données
        $commandes = $entityManager
        ->getRepository(Commandes::class)
        ->findAll();
    
         // Créer un nouveau fichier Excel
         $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
         $sheet = $spreadsheet->getActiveSheet();
         
        // Ajouter les en-têtes
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Date ');
        $sheet->setCellValue('C1', 'Mode Payement');
        $sheet->setCellValue('D1', 'Adresse');
    
        // Remplir les données
        $row = 2;
        foreach ($commandes as $commande) {
            $sheet->setCellValue('A' . $row, $commande->getId());
            $sheet->setCellValue('B' . $row, $commande->getDate()->format('Y-m-d'));
            // Ajouter le lien vers l'image
            $sheet->setCellValue('C' . $row, $commande->getModePayement());
            $sheet->setCellValue('D' . $row, $commande->getAdresse());

            $row++;
        }
        // Créer le writer pour Excel
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        // Enregistrer le fichier Excel
        $writer->save('commande_export.xlsx');
        // Retourner une réponse avec le fichier Excel
        return $this->file('commande_export.xlsx');
}


// You'll also need to ensure that you have routes and corresponding controller methods for 'order_not_found' and 'payment_init_failure'.


}
