<?php

namespace App\Form\CreateSortie;

use App\Entity\Lieu;
use App\Entity\Ville;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('street', TextType::class, [
                'label' => 'Rue : ',
                'required' => true,
                'attr' => [
                    'class' => 'form-group d-flex flex-column mb-3'
                ]
            ])
            ->add('latitude', IntegerType::class, [
                'label' => 'Latitude : ',
                'required' => true,
                'attr' => [
                    'min' => -90,
                    'max' => 90, // Valeur maximale
                    'step' => 1,
                    'class' => 'form-control mb-3'
                ],
            ])
            ->add('longitude', IntegerType::class, [
                'label' => 'Longitude : ',
                'required' => true, // ou false selon que le champ est obligatoire ou non
                'attr' => [
                    'min' => -180, // Valeur minimale
                    'max' => 180, // Valeur maximale
                    'step' => 1, // L'intervalle entre les valeurs (1 pour les entiers)
                    'class' => 'form-control mb-3'
                ],
            ])
        ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
