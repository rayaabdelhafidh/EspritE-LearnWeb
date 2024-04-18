<?php

namespace App\Form;

use App\Entity\Quizz;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Question;
use App\Entity\Options;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class QuizzType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('description',TextType::class, [
            'constraints' => [
                new Length(['min' => 2, 'minMessage' => 'Le description doit contenir au moins {{ limit }} caractères.']),
            ],
            ]) 
        ->add('matiere',TextType::class, [
            'constraints' => [
                new Length(['min' => 2, 'minMessage' => 'Le matier doit contenir au moins {{ limit }} caractères.']),
            ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quizz::class,
        ]);
    }
}
