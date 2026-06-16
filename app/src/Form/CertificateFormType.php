<?php

namespace App\Form;

use App\Entity\CategoryType;
use App\Entity\Certificate;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CertificateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('deliverySite')
            ->add('gpsRef')
            ->add('round')
            ->add('project')
            ->add('deliveryDate')
            ->add('hasDerogation')
            ->add('isHare')
            ->add('associatedDerogation')
            ->add('restrictive')
            ->add('comodif')
            ->add('comments')
            ->add('readyToDeliver')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('categoryType', EntityType::class, [
                'class' => CategoryType::class,
                'choice_label' => 'id',
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
