<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; // Import EntityType class
use App\Entity\Quizz; // Import Quizz entity if not already imported
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('content',TextType::class, [
            'constraints' => [
                new Length(['min' => 2, 'minMessage' => 'Le contenu de question doit contenir au moins {{ limit }} caractères.']),
            ],
            ]) 
        ->add('score',IntegerType::class, [
            'constraints' => [
                new NotBlank(['message' => 'Le score est requise.']),
                new PositiveOrZero(['message' => 'Le score être un entier positif ou zéro.']),
            ],
        ])

       
    
    
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
            
        ]);
    }
}
