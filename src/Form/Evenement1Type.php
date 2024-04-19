<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Evenement1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', null, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Nom',
        ])
        ->add('date', null, [
            'attr' => ['class' => 'form-control'],
            'widget' => 'single_text', // This is for Symfony to use the HTML5 date picker
            'label' => 'Date',
        ])
        ->add('prix', null, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Prix',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
