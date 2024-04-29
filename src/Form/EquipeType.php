<?php

namespace App\Form;

use App\Entity\Equipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Range;

class EquipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(),
                   
                ]
            ])
            ->add('region', TextType::class, [
                'label' => 'Region',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(),
                    
                ]
            ])
            ->add('ligue', TextType::class, [
                'label' => 'Ligue',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(),
                    
                ]
            ])
            ->add('classement', IntegerType::class, [
                'label' => 'Classement',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(),
                    new Type('integer'),
                    
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipe::class,
        ]);
    }
}