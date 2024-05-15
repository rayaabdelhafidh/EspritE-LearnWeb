<?php

namespace App\Form;

use App\Entity\Matiere;
use App\Entity\Plandetude;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType; 
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MatiereType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomm')
            ->add('idenseignant')
            ->add('nbrheure')
            ->add('coefficient')
            ->add('semester')
            ->add('credit')
            ->add('idplandetude', EntityType::class, [
                'class' => Plandetude::class,
                'choice_label' => 'nomprogramme', // Remplacez 'nomm' par le nom de la propriété que vous voulez afficher dans le champ de sélection
                // Autres options si nécessaire
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Matiere::class,
        ]);
    }
}
