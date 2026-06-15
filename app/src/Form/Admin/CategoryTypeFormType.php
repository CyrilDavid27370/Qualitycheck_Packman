<?php

namespace App\Form\Admin;

use App\Entity\Category;
use App\Entity\CategoryType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoryTypeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du type',
                'attr' => [
                    'placeholder' => 'Ex: Moteur AFM',
                    'class' => 'form-control bg-dark text-light border-secondary'
                ],
                'constraints' => [
                    new NotBlank(message: 'Le nom du type est obligatoire.'),
                    new Length(
                        min: 2, minMessage: 'Le nom du type doit faire au moins {{ limit }} caractères.',
                        max: 50, maxMessage: 'Le nom du type ne doit pas dépasser {{ limit }} caractères.',
                    )
                ],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Catégorie parente',
                'attr' => [
                    'class' => 'form-control bg-dark text-light border-secondary'
                ],
                'constraints' => [
                    new NotBlank(message: 'La catégorie est obligatoire.'),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CategoryType::class,
        ]);
    }
}
