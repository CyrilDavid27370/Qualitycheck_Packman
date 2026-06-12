<?php

namespace App\Form\Admin;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {   
        // On récupère l'option is_edit pour adapter le formulaire
        $isEdit = $options['is_edit'];

        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom de famille',
                    'class' => 'form-control bg-dark text-light border-secondary',
                ],
                'constraints' => [
                    new NotBlank(message: 'Le nom est obligatoire.'),
                    new Length(
                        min: 2, minMessage: 'Le nom doit faire au moins {{ limit }} caractères.',
                        max: 50, maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères.',
                    ),               
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Prénom',
                    'class' => 'form-control bg-dark text-light border-secondary',
                ],
                'constraints' => [
                    new NotBlank(message: 'Le prénom est obligatoire.'),
                    new Length(
                        min: 2, minMessage: 'Le nom doit faire au moins {{ limit }} caractères.',
                        max: 50, maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères.',
                    ),
                ],
            ])
            ->add('matricule', TextType::class, [
                'label' => 'Matricule',
                'attr' => [
                    'placeholder' => 'Ex: A670997',
                    'class' => 'form-control bg-dark text-light border-secondary',
                ],
                'constraints' => [
                    new NotBlank(message: 'Le matricule est obligatoire.'),
                    new Length(
                        min: 2, minMessage: 'Le matricule doit faire au moins {{ limit }} caractères.',
                        max: 50, maxMessage: 'Le matricule ne peut pas dépasser {{ limit }} caractères.',
                    ),
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Role',
                'choices' => [
                    'Analyste Qualité' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                'expanded' => true,
                'attr' => ['class' => 'form-check'],
            ])
            ->add('active', CheckboxType::class, [
                'label' => 'Compte actif',
                'required' => false,
                'attr' => ['class' => 'form-check-input'],

            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'label' => $isEdit
                ? "Nouveau mot de passe (laisser vide pour conserver l'actuel)"
                : 'Mot de passe',
                'required' => !$isEdit,
                'attr' => [
                    'placeholder' => 'Mot de passe',
                    'autocomplete' => 'nouveau mot de passe',
                    'class' => 'form-control bg-dark text-light border-secondary',
                ],
                'constraints' => $isEdit ? [] : [
                    new NotBlank(message: 'Le mot de passe est obligatoire.'),
                    new Length(min:12),
                    new Regex(
                    pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&_\-#])[A-Za-z\d@$!%*?&_\-#]{6,}$/', 
                    message: 'Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial.',
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            // Option personnalisée pour adapter le formulaire création/modification
            'is_edit'    => false,
        ]);
    }
}
