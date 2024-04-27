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
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class CourType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
        ->add('titre', TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Le titre est requis.']),
                new Length(['min' => 2, 'max' => 255, 'minMessage' => 'Le titre doit contenir au moins {{ limit }} caractères.', 'maxMessage' => 'Le titre ne peut pas dépasser {{ limit }} caractères.']),
            ],
        ])
        ->add('description', TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'La description est requise.']),
                new Length(['min' => 2, 'max' => 500, 'minMessage' => 'La description doit contenir au moins {{ limit }} caractères.', 'maxMessage' => 'La description ne peut pas dépasser {{ limit }} caractères.']),
            ],
        ])
        ->add('duree', IntegerType::class, [
            'constraints' => [
                new NotBlank(['message' => 'La durée est requise.']),
                new PositiveOrZero(['message' => 'La durée doit être un entier positif ou zéro.']),
            ],
        ])
        ->add('objectif', TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'L\'objectif est requis.']),
                new Length(['min' => 2, 'max' => 500, 'minMessage' => 'L\'objectif doit contenir au moins {{ limit }} caractères.', 'maxMessage' => 'L\'objectif ne peut pas dépasser {{ limit }} caractères.']),
            ],
        ])
       // ->add('image',TextType::class)
        ->add('image', FileType::class, [
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
       /* ->add('coursPdfUrl', TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'L\'URL PDF est requise.']),
                new Length(['min' => 2, 'max' => 500, 'minMessage' => 'L\'URL PDF doit contenir au moins {{ limit }} caractères.', 'maxMessage' => 'L\'URL PDF ne peut pas dépasser {{ limit }} caractères.']),
            ],
        ])*/
        ->add('coursPdfUrl', FileType::class, [
            'label' => 'PDF du cours',
            'mapped' => false, // Ceci indique que ce champ n'est pas associé à une propriété de l'entité
            'required' => false, // Rend le champ facultatif
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'application/pdf', // Accepte uniquement les fichiers PDF
                    ],
                    'mimeTypesMessage' => 'Veuillez télécharger un fichier PDF valide.',
                ])
            ],
        ])
        ->add('idmatiere', EntityType::class, [
            'class' => Matiere::class,
            'choice_label' => 'nomm', // Remplacez 'nomm' par le nom de la propriété que vous voulez afficher dans le champ de sélection
            // Autres options si nécessaire
            'constraints' => [
                new NotBlank(['message' => 'La matière est requise.']),
            ],
        ])
        ->add('note')
        ->add('nblike');
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cour::class,
        ]);
    }
}
