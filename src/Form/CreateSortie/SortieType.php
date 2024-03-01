<?php

namespace App\Form\CreateSortie;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la sortie : ',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mb-3'
                ]
            ])
            ->add('startDate', DateTimeType::class, [
                'label' => 'Date et heure de la sortie',
                'widget' => 'single_text', // Permet de choisir une date avec un seul champ.
                'html5' => true, // Utilise l'input type="date" HTML5.
                'required' => true,
                'attr' => ['class' => 'form-control mb-3']
            ])
            ->add('limiteDateInscription', DateType::class, [
                'label' => 'Date limite d\'inscription',
                'widget' => 'single_text', // Permet de choisir une date avec un seul champ.
                'html5' => true, // Utilise l'input type="date" HTML5.
                'required' => true,
                'attr' => ['class' => 'form-control mb-3']
            ])
            ->add('maxInscriptionsNumber', IntegerType::class, [
                'label' => 'Nombre de place',
                'required' => true,
                'attr' => [
                    'min' => 0, // Valeur minimale
                    'max' => 150, // Valeur maximale
                    'step' => 1, // L'intervalle entre les valeurs (1 pour les entiers)
                    'class' => 'form-control mb-3'
                ],
                // Vous pouvez également ajouter des contraintes de validation ici
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Durée : ',
                'required' => true,
                'attr' => [
                    'placeholder' => '90',
                    'class' => 'form-control mb-3',
                    'step' => 30,
                ]
            ])
            ->add('infosSortie', TextareaType::class, [
                'label' => 'Description et infos : ',
                'required' => true,
                'attr' => ['rows' => 4, 'class' => 'form-control mb-3'],
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'name',
                'label' => 'Campus',
                'attr' => [
                    'class' => 'form-control mb-3'
                ]
            ])
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => 'name',
                'required'=>true,
                'mapped'=>false,
            ])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => function (Lieu $lieu) {
                    return sprintf('%s - %s', $lieu->getName(), $lieu->getVille()->getName());
                },
            ])





        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
