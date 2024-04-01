<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\Club;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nomevenement',TextType::class)
        ->add('dateevenement', DateType::class)
        ->add('lieuevenement',TextType::class)
        ->add('prixevenement',TextType::class)
        ->add('afficheevenement', FileType::class, [
            'label' => 'Affiche de l\'événement',
            'mapped' => false, // Tell Symfony not to try to map this field to an entity property
            'required' => false, // Allow the field to be empty
        ])
        
        ->add('club', EntityType::class, [
            'class' => Club::class,
            'choice_label' => 'nomclub', // Remplacez 'nomm' par le nom de la propriété que vous voulez afficher dans le champ de sélection
            // Autres options si nécessaire
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
