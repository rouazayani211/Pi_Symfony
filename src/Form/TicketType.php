<?php

namespace App\Form;

use App\Entity\Ticket;
use App\Entity\Reservation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; // Ajout de l'importation de la classe EntityType
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('NumSiege', null, [
                'constraints' => [
                    new Positive(['message' => 'Le NUM DE SIEGE doit être positif.']),
                    // Vous pouvez ajouter d'autres contraintes de validation selon vos besoins
                ],
            ])
            ->add('prix', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Le champ prix ne doit pas être vide.']),
                    new Type(['type' => 'float', 'message' => 'Le prix doit être un nombre décimal.']),
                    // Vous pouvez ajouter d'autres contraintes de validation selon vos besoins
                ],
            ])
            ->add('dateEvenement', DateType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    // Ajoute une contrainte NotBlank pour vérifier que la date est renseignée
                    // Vous pouvez ajouter d'autres contraintes de validation selon vos besoins
                ],
            ])
            ->add('statutTicket', ChoiceType::class, [
                'choices' => [
                    'En Attente' => 'En Attente',
                    'Confirmé' => 'Confirmé',
                    'Annulé' => 'Annulé',
                ],
                'constraints' => [
                    new Choice(['choices' => ['Confirmé', 'Annulé', 'En Attente'], 'message' => 'Le statut du ticket n\'est pas valide.']),
                ],
            ])
            ->add('idReservation', EntityType::class, [
                'class' => Reservation::class,
                'choice_label' => 'id', // Utilisation de l'ID comme étiquette pour les options
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
