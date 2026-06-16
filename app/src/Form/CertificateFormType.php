<?php

namespace App\Form;

use App\Entity\Certificate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CertificateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('deliverySite', TextType::class, [
                'label' => 'site de livraison',
                'attr' => [
                    'placeholder' => 'CRPV',
                    'class' => 'form-control bg-dark text-light border-secondary',
                ],
                'constraints' => [
                    new NotBlank(message: 'Le site de livraison est obligatoire.')
                ],
            ])
            ->add('gpsRef', TextType::class, [
                'label' => 'Référence GPS',
                'attr' => [
                    'placeholder' => 'Ex: HAE 1002 SC B2 1645',
                    'class' => 'form-control bg-dark text-light border-secondary',
                ],
                'constraints' => [
                    new NotBlank(message: 'la référence GPS est obligatoire.')
                ],
            ])
            ->add('round', TextType::class, [
                'label' => 'Boucle',
                'attr' => [
                    'placeholder' => 'Ex: SC B2',
                    'class' => 'form-control bg-dark text-light border-secondary',
                ],
                'constraints' => [
                    new NotBlank(message: 'La boucle est obligatoire.'),
                ],
            ])
            ->add('project', TextType::class, [
                'label' => 'Affaire',
                'attr' => [
                    'placeholder' => 'Ex: FB2A',
                    'class' => 'form-control bg-dark text-light border-secondary',
                ],
                'constraints' => [
                    new NotBlank(message: "L'affaire est obligatoire.")
                ],
            ])
            ->add('deliveryDate', DateType::class, [
                'label' => 'Date de livraison',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control bg-dark text-light border-secondary',
                ],
                'constraints' => [
                    new NotBlank(message: 'La date de livraison est obligatoire.'),
                ],
            ])
            ->add('hasDerogation', CheckboxType::class, [
                'label' => "Le support de validation a fait l'objet de derogation(s).",
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input'
                ],
            ])
            ->add('isHare', CheckboxType::class, [
                'label' => 'Le support de validation est-il un lièvre.',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input'
                ],
            ])
            ->add('associatedDerogation', TextType::class, [
                'label' => 'Dérogation(s) associée(s)',
                'required' => false,
                'attr' => [
                    'placeholder' => 'N° item(s) NC & dérogation(s)',
                    'class' => 'form-control bg-dark text-light border-secondary',
                ],
            ])
            ->add('restrictive', TextType::class, [
                'label' => 'N° Restrictive(s)',
                'required' => false,
                'attr' => [
                    'placeholder' => 'N° restrictive(s)',
                    'class' => 'form-control bg-dark text-light border-secondary',
                ],
            ])
            ->add('comodif', TextType::class, [
                'label' => 'Comodif(s)',
                'required' => false,
                'attr' => [
                    'placeholder' => 'N° de comodif(s)',
                    'class' => 'form-control bg-dark text-light border-secondary',
                ],
            ])
            ->add('comments', TextareaType::class, [
                'label' => 'Commentaires',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Commentaires...',
                    'class' => 'form-control bg-dark text-light border-secondary',
                    'rows' => 3,
                ],
            ])
            ->add('readyToDeliver', CheckboxType::class, [
                'label' => 'Bon à livrer',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Certificate::class,
        ]);
    }
}
