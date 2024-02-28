<?php

namespace App\Form\User;

use App\Entity\Campus;
use App\Entity\Sortie;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class)
            ->add('lastname', TextType::class)
            ->add('firstName', TextType::class)
            ->add('phoneNumber', TextType::class)
            ->add('email', TextType::class)
//            ->add('profilePictureFile', FileType::class, [
//                'label' => 'Photo de profil (Image file)',
//                'required' => false,
//            ])
            ->add('pictureName', FileType::class, [
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
