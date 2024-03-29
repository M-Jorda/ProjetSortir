<?php

namespace App\Form\Sortie;

use App\DTO\SortieDTO;
use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieDTOType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'name',
                'placeholder'=>'Tout',
                'required'=>false,
            ])
            ->add('name', TextType::class, [
                'label' => 'Rechercher par nom :',
                'required' => false,
                'empty_data' => '',

            ])
            ->add('filterDate', DateType::class, [
                'label' => 'Entre :',
                'required' => false,
                'html5' => true,
                'widget' => 'single_text',

            ])
            ->add('filterDateMax',DateType::class, [
                'label'=> 'Et :',
                'required'=>false,
                'html5' => true,
                'widget' => 'single_text',

            ])
            ->add('checkboxOrga',CheckboxType::class,[
                'label'=> 'Sorties dont je suis l\'organisateur/trice',
                'required'=>false,

            ])
            ->add('checkBoxInscrit',CheckboxType::class,[
                'label'=>'Sorties auxquelles je suis inscrit/e',
                'required'=>false,
            ])
            ->add('checkBoxNotInscrit',CheckboxType::class,[
                'label'=>'Sorties auxquelles je ne suis pas inscrit/e',
                'required'=>false,
            ])
            ->add('sortiePasse',CheckboxType::class,[
                'label'=>'Sorties passées',
                'required'=>false,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SortieDTO::class,
             'allow_extra_fields' => true,
        ]);
    }
}
