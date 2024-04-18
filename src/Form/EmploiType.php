<?php
namespace App\Form;

use App\Entity\Emploi;
use App\Entity\Salle;
use App\Entity\Classe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EmploiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('premierdate', null, [
                'label' => 'De : ',
            ])
            ->add('dernierdate', null, [
                'label' => 'Jusqu\'à : ',
            ])
            ->add('salle', EntityType::class, [
                'class' => Salle::class,
                'label' => 'Salle : ',
            ])
            ->add('classe', EntityType::class, [
                'class' => Classe::class,
                'label' => 'Classe : ',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Emploi::class,
        ]);
    }
}
