<?php

namespace App\Form;

use App\DTO\SortieDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieDTOType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Rechercher par nom :',
                'required' => false,
            ])
            ->add('filterDate', DateType::class, [
                'label' => 'Filtrer par date :',
                'required' => false,
            ])
            ->add('filterDateMax',DateType::class, [
                'label'=> 'Filtrer par date :',
                'required'=>false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SortieDTO::class
        ]);
    }
}
