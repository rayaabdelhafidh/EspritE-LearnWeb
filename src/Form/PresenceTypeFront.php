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

class PresenceTypeFront extends AbstractType
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
        ->add('idClasse', TextType::class, [
            'disabled' => true,
            'data' => $options['idclassname'], 
            // Any other options you may have
        ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Presence::class,
            'idclassname' => null,

        ]);
    }
}
