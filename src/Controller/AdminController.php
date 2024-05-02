<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/app', name: 'app')]
    public function index(): Response
    {   
        

        return $this->render('admin/index.html.twig',
         ['controller_name' => 'AdminController', ]);
    }

    #[Route('/listusers', name: 'listusers')]
    public function listusers(UserRepository $userRepository): Response
    {
        $user = $userRepository->findAll();
        return $this->render('admin/list_users.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/deleteuser/{id}',name:'delete_user')]
    public function deleteuser(  $id , ManagerRegistry $doctrine):Response
    {
       $em=$doctrine->getManager();
       $repo=$doctrine->getRepository(User::class);
       $user=$repo->find($id);
       $em->remove($user);
       $em->flush();
       return $this ->redirectToRoute('listusers');
    }


#[Route("/edit/{id}", name:'edit')]
public function editUser($id, Request $request, EntityManagerInterface $em, ManagerRegistry $doctrine): Response 
{
    $repo = $doctrine->getRepository(User::class);
    $user = $repo->find($id);
    $em = $doctrine->getManager();
    
    // Remove the 'plainPassword' field from the form
    $form = $this->createFormBuilder($user)
        ->add('email')
        ->add('name')
        ->add('lastname')
        ->add('age')
        ->add('cin')
        ->add('wording')
        ->add('save', SubmitType::class, [
            'attr' => ['class' => 'savebutton']
        ])
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute("listusers");
    }

    return $this->render('admin/edit.html.twig', ['form' => $form->createView()]);
}

#[Route("/search", name: 'search_user')]
public function searchUser(Request $request): Response
{
    $name = $request->query->get('name');

    $entityManager = $this->getDoctrine()->getManager();
    $userRepository = $entityManager->getRepository(User::class);

    $user = $userRepository->createQueryBuilder('u')
        ->andWhere('u.name LIKE :name')
        ->setParameter('name', '%' . $name . '%')
        ->getQuery()
        ->getResult();

    return $this->render('admin/list_users.html.twig', [
        'user' => $user,
    ]);
}


#[Route('/sort-by-last-name', name: 'listuserssorted')]
    public function sortlistuser(UserRepository $userRepository): Response
    {
        // Fetch users from the repository in alphabetical order
        $users = $userRepository->findBy([], ['lastname' => 'ASC']);
    
        return $this->render('admin/list_users.html.twig', [
            'user' => $users,
        ]);
    }

}


