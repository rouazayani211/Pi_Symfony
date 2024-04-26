<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'constraints' => [
                new Assert\NotBlank(['message' => 'Email cannot be blank.']),
                new Assert\Email(['message' => 'Please enter a valid email address.']),
            ],
        ])
        ->add('name', TextType::class, [
            'constraints' => [
                new Assert\NotBlank(['message' => 'Name cannot be blank.']),
            ],
        ])
        ->add('lastname', TextType::class, [
            'constraints' => [
                new Assert\NotBlank(['message' => 'Lastname cannot be blank.']),
            ],
        ])
        ->add('age', IntegerType::class, [
            'constraints' => [
                new Assert\NotBlank(['message' => 'Age cannot be blank.']),
                new Assert\GreaterThan(['value' => 0, 'message' => 'Age must be greater than 0.']),
            ],
        ])
        ->add('cin', IntegerType::class, [
            'constraints' => [
                new Assert\NotBlank(['message' => 'CIN cannot be blank.']),
                new Assert\Length(['min' => 8, 'max' => 8, 'exactMessage' => 'CIN must be exactly 8 digits.']),
                new Assert\Type(['type' => 'numeric', 'message' => 'CIN must be numeric.']),
            ],
        ])
        ->add('wording')
        ->add('agreeTerms', CheckboxType::class, [
            'mapped' => false,
            'constraints' => [
                new Assert\IsTrue(['message' => 'You should agree to our terms.']),
            ],
        ])
        ->add('plainPassword', PasswordType::class, [
            'mapped' => false,
            'attr' => ['autocomplete' => 'new-password'],
            'constraints' => [
                new Assert\NotBlank(['message' => 'Please enter a password.']),
                new Assert\Length([
                    'min' => 6,
                    'minMessage' => 'Your password should be at least {{ limit }} characters.',
                    'max' => 4096, // max length allowed by Symfony for security reasons
                ]),
            ],
        ]);
}
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
