<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Entity\User; // Ajout de l'importation de la classe User
use Symfony\Bridge\Doctrine\Form\Type\EntityType; // Ajout de l'importation de la classe EntityType

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class reservation1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('dateDeReservation', DateType::class, [
            'widget' => 'single_text',
            'data' => new \DateTime(), // Préremplir avec la date du jour
            'attr' => ['readonly' => true],// Rendre le champ non modifiable
            // Ajoutez d'autres options si nécessaire
        ])
        ->add('nombreTicket', null, [
            'constraints' => [
                new Positive(['message' => 'Le nombre de tickets doit être positif.']),
                new LessThanOrEqual(['value' => 5, 'message' => 'Le nombre de tickets ne doit pas dépasser 20.']),
                // Vous pouvez ajouter d'autres contraintes de validation selon vos besoins
            ],
        ])
        ->add('statutReservation', ChoiceType::class, [
            'choices' => [ // Remove the choices array
                'En Attente' => 'En Attente', // Set "En Attente" as the only option
            ],
            'constraints' => [
                new NotBlank() // Optional: Ensure a value is always selected
            ],
        ])
            ->add('idUser', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id', // Utilisation de l'ID comme étiquette pour les options
            ]);
        
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
