<?php

namespace App\Form;

use App\Entity\Options;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Question;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; // Import EntityType class
use App\Entity\Quizz; // Import Quizz entity if not already imported
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class OptionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('option_content',TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Le contenue de option est requis.']),
                new Length(['min' => 2, 'minMessage' => 'Le contenu de option doit contenir au moins {{ limit }} caractères.']),
            ],
            ])
            ->add('is_correct', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'expanded' => true,
        
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Options::class,
        ]);
    }
}
