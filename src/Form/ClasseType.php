<?php

namespace App\Form;

use App\Entity\Classe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClasseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Nom de la classe',
        ])
        ->add('nombre', IntegerType::class, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Nombre d\'étudiants',
        ])
        ->add('niveau', ChoiceType::class, [
            'choices' => [
                '1A' => '1A',
                '2A' => '2A',
                '2P' => '2P',
                '3A' => '3A',
                '3B' => '3B',
                '4A' => '4A',
                '5A' => '5A',
            ],
            'attr' => ['class' => 'form-select'],
            'label' => 'Niveau de la classe',
        ])
       
        ->add('filiere', ChoiceType::class, [
            'choices' => [
                'TIC' => 'TIC',
                'GC' => 'GC',
                'BUSINESS' => 'BUSINESS',
            ],
            'attr' => ['class' => 'form-select'],
            'label' => 'Filière',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Classe::class,
        ]);
    }
}
