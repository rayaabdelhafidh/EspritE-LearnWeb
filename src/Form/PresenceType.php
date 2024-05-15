<?php

namespace App\Form;

use App\Entity\Classe;
use App\Entity\Presence;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PresenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('date', DateTimeType::class, [
            'required' => true,
        ])
        ->add('seance', TextType::class, [
            'required' => true,
            'attr' => ['class' => 'form-control'],
        ])
        ->add('idClasse', EntityType::class, [
            'class' => Classe::class,
            'choice_label' => function (Classe $classe) {
                return $classe->getNom(); // Assuming you want to show the 'nom' property as the label
            },
            'placeholder' => 'Choose a class',
            'attr' => ['class' => 'form-select'],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Presence::class,
        ]);
    }
}
