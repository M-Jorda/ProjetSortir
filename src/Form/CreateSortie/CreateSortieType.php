<?php

namespace App\Form\CreateSortie;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sortie', SortieType::class, [
                'label' => false,
            ])
            ->add('lieu', LieuType::class, [
                'label' => false,
            ])
            ->add('ville', VilleType::class, [
                'label' => false,
            ])
            ->getForm()
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
