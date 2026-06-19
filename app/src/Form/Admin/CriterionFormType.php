<?php

namespace App\Form\Admin;

use App\Entity\Criterion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class CriterionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('itemNumber', IntegerType::class, [
                'label' => 'N° item',
                'attr' => [
                    'placeholder' => 'Ex:1',
                    'class' => 'form-control bg-dark text-light border-secondary',
                    'min' => 1,
                ],
                'constraints' => [
                    new NotBlank(message: "Le numéro d'item est obligatoire."),
                    new Positive(message: 'Le numéro doit etre positif.'),
                ],
            ])
            ->add('label', TextareaType::class, [
                'label' => 'Libellé du critère',
                'attr' => [
                    'placeholder' => 'Ex:Présence fiche GPS',
                    'class' => 'form-control bg-dark text-light border-secondary',
                    'rows' => 3,
                ],
                'constraints' => [
                    new NotBlank(message: 'Le libellé est obligatoire.'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Criterion::class,
        ]);
    }
}
