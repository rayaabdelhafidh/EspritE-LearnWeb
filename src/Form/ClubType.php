<?php

namespace App\Form;

use App\Entity\Club;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

class ClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomclub',TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le nom du club est requis.']),
                    new Length(['min' => 2, 'minMessage' => 'Le nom du club doit contenir au moins {{ limit }} caractères.']),
                ],
                ])
                
            ->add('datefondation', DateType::class,[
                'constraints' => [
                    new LessThanOrEqual([
                    'value' => 'today',
                    'message' => 'la date doit etre inferieure a celle d aujourrdhui.',
                    ]),
            ],
            ])
            ->add('typeactivite',TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le nom du programme est requis.']),
                    new Length(['min' => 2, 'minMessage' => 'Le type d activite doit contenir au moins {{ limit }} caractères.']),
                ],
                ])
            ->add('description',TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le nom du programme est requis.']),
                    new Length(['min' => 2, 'minMessage' => 'La description doit contenir au moins {{ limit }} caractères.']),
                ],
                ])
            ->add('nbmembres',IntegerType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le nombre de membres est requise.']),
                    new PositiveOrZero(['message' => 'Le nombre de membres être un entier positif ou zéro.']),
                ],
            ])
            ->add('active',ChoiceType::class,[
                'choices'  => [
                    'Oui' => 'Oui',
                    'Non' => 'Non',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Club::class,
        ]);
    }
}
