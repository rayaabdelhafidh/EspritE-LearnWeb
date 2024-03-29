<?php

namespace App\Form;

use App\Entity\Cour;
use App\Entity\Matiere;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType; 
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class CourType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
        ->add('titre',TextType::class)
        ->add('description',TextType::class)
        ->add('duree')
        ->add('objectif',TextType::class)
       // ->add('image',TextType::class)
        ->add('image',FileType::class, [
            'label' => 'Votre image',

            // unmapped means that this field is not associated to any entity property
            'mapped' => false,

            // make it optional so you don't have to re-upload the PDF file
            // every time you edit the Product details
            'required' => false,

            // unmapped fields can't define their validation using annotations
            // in the associated entity, so you can use the PHP constraint classes
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/jpg',
                        'image/png'

                    ],
                    'mimeTypesMessage' => 'Please upload a valid Image',
                ])
            ],
        ])

        ->add('coursPdfUrl',TextType::class)
        //->add('idMatiere')
      /*  ->add('idMatiere', EntityType::class, [
            'class' => Matiere::class,
            'choice_label' => 'nomm', // Remplacez 'nomm' par le nom de la propriété que vous voulez afficher dans le champ de sélection
            // Autres options si nécessaire
        ])*/
        ->add('idmatiere', EntityType::class, [
            'class' => Matiere::class,
            'choice_label' => 'nomm', // Remplacez 'nomm' par le nom de la propriété que vous voulez afficher dans le champ de sélection
            // Autres options si nécessaire
        ])
        ->add('note')
        ->add('nblike')
       
       
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cour::class,
        ]);
    }
}
