<?php

namespace App\Form;

use App\Entity\Plandetude;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class PlandetudeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomProgramme', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le nom du programme est requis.']),
                    new Length(['min' => 2, 'minMessage' => 'Le nom du programme doit contenir au moins {{ limit }} caractères.']),
                ],
            ])
            ->add('niveau', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le niveau est requis.']),
                    new Length(['min' => 2, 'minMessage' => 'Le niveau doit contenir au moins {{ limit }} caractères.']),
                ],
            ])
            ->add('dureeTotal')
            ->add('creditsRequisTotal')
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plandetude::class,
        ]);
    }
}
