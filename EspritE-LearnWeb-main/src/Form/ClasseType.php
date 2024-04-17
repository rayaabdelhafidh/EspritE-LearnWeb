<?php

namespace App\Form;

use App\Entity\Classe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ClasseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {   $builder
        ->add('nomClasse')
        ->add('filiere', ChoiceType::class, [
            'choices' => [
                'TIC' => 'TIC',
                'Business' => 'Business',
                'GC' => 'GC', ],
            'placeholder' => '', ])
        ->add('nbreEtudi')
        ->add('niveaux', ChoiceType::class, [
            'choices' => [
                '1A' => '1A',
                '2A' => '2A',
                '2P' => '2P',
                '3A' => '3A',
                '3B' => '3B',
                '4A' => '4A',
                '5A' => '5A',
            ],
            'placeholder' => '',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Classe::class,
        ]);
    }
}
