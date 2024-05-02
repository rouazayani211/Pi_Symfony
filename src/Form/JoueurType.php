<?php

namespace App\Form;

use App\Entity\Joueur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Equipe;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Range;

class JoueurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(),
<<<<<<< HEAD
                 
=======
                    new Type('string'),
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]*$/',
                       
                    ])
>>>>>>> c0a6901e20af18834aaf204d50cd2c2b74f48da8
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(),
<<<<<<< HEAD
                   
=======
                    new Type('string'),
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]*$/',
                       
                    ])
>>>>>>> c0a6901e20af18834aaf204d50cd2c2b74f48da8
                ]
            ])
            ->add('age', IntegerType::class, [
                'label' => 'Âge',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(),
<<<<<<< HEAD
                    
=======
                    new Type('integer'),
                    new Range([
                        'min' => 15,
                        'max' => 50,
                      
                   
                    ]),
>>>>>>> c0a6901e20af18834aaf204d50cd2c2b74f48da8
                ]
            ])
            ->add('numero', IntegerType::class, [
                'label' => 'Numéro',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(),
<<<<<<< HEAD
                   
=======
                    new Type('integer'),
                    new Range([
                        'min' => 1,
                        'max' => 99,
                        
                       
                    ]),
>>>>>>> c0a6901e20af18834aaf204d50cd2c2b74f48da8
                ]
            ])
            ->add('position', TextType::class, [
                'label' => 'Position',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(),
<<<<<<< HEAD
                   
=======
                    new Type('string'),
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]*$/',
                        
                    ])
>>>>>>> c0a6901e20af18834aaf204d50cd2c2b74f48da8
                ]
            ])
            ->add('equipe', EntityType::class, [
                'class' => Equipe::class,
                'label' => 'Équipe',
                'choice_label' => 'nom',
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Joueur::class,
        ]);
    }
}
