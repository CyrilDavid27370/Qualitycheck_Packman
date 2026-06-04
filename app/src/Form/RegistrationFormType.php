<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr'  => [
                    'placeholder' => 'Votre nom',
                    'class'       => 'form-control bg-dark text-light border-secondary',
                ],
                'constraints' => [
                    new NotBlank(message: 'Le nom est obligatoire.'),
                    new Length(
                        min: 2, minMessage: 'Le nom doit faire au moins {{ limit }} caractères.',
                        max: 50, maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères.'),
                ],
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr'  => [
                    'placeholder' => 'Votre prénom',
                    'class'       => 'form-control bg-dark text-light border-secondary',
                ],
                'constraints' => [
                    new NotBlank(message: 'Le prénom est obligatoire.'),
                    new Length(
                        min: 2, minMessage: 'Le prénom doit faire au moins {{ limit }} caractères.',
                        max: 50, maxMessage: 'Le prénom ne peut pas dépasser {{ limit }} caractères.'),
                ],
            ])
            ->add('matricule', TextType::class, [
                'label' => 'Matricule',
                'attr'  => [
                    'placeholder'  => 'Ex: CD001',
                    'autocomplete' => 'username',
                    'class'        => 'form-control bg-dark text-light border-secondary',
                ],
                'constraints' => [
                    new NotBlank(message: 'Le matricule est obligatoire.'),
                    new Length(
                        min: 3,
                        minMessage: 'Le matricule doit faire au moins {{ limit }} caractères.',
                        max: 50,
                        maxMessage: 'Le matricule ne peut pas dépasser {{ limit }} caractères.',
                    ),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'mapped'         => false,
                'type'           => PasswordType::class,
                'first_options'  => [
                    'label' => 'Mot de passe',
                    'attr'  => [
                        'placeholder'  => 'Mot de passe',
                        'autocomplete' => 'new-password',
                        'class'        => 'form-control bg-dark text-light border-secondary',
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe',
                    'attr'  => [
                        'placeholder'  => 'Mot de passe',
                        'autocomplete' => 'new-password',
                        'class'        => 'form-control bg-dark text-light border-secondary',
                    ],
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'constraints'     => [
                    new NotBlank(message: 'Le mot de passe est obligatoire.'),
                    new Length(
                        min: 12,
                        minMessage: 'Le mot de passe doit faire au moins {{ limit }} caractères.',
                        max: 4096,
                    ),
                    new Regex(
                            pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&_\-#])[A-Za-z\d@$!%*?&_\-#]{6,}$/',
                            message: 'Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial (@$!%*?&_-#).',
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
