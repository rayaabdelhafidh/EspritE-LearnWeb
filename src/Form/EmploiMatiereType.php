<?php
namespace App\Form;

use App\Entity\EmploiMatiere;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use App\Entity\Matiere;

class EmploiMatiereType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('matiere', EntityType::class, [
                'class' => Matiere::class,
                'choice_label' => 'nomm',
                'label' => 'Matière : ', 
            ])
            ->add('starttime', TimeType::class, [
                'widget' => 'single_text',
                'placeholder' => 'Choisir un horaire',
                'label' => 'heure de début de la séance : ',
            ])
            ->add('endtime', TimeType::class, [
                'widget' => 'single_text',
                'placeholder' => 'Choisir un horaire',
                'label' => 'Heure de fin de la séance : ',
            ])
            ->add('dayofweek', ChoiceType::class, [
                'choices' => [
                    'Monday' => 'Monday',
                    'Tuesday' => 'Tuesday',
                    'Wednesday' => 'Wednesday',
                    'Thursday' => 'Thursday',
                    'Friday' => 'Friday',
                ],
                'placeholder' => 'Choisir un jour',
                'label' => 'Jour de la semaine : ', 
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EmploiMatiere::class,
        ]);
    }
}
