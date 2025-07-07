<?php

namespace App\Form;

use App\Form\Model\Combat;
use App\Form\Model\CustomFormData;
use App\Validator\TotalEquals;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CombatForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('initiative', IntegerType::class, [
                'attr' => [
                    'min' => 0,
                    'max' => 3
                ]
            ])
            ->add('melee', IntegerType::class, [
                'label' => 'Mêlée',
                'attr' => [
                    'min' => 0,
                    'max' => 3
                ]
            ])
            ->add('tir', IntegerType::class, [
                'attr' => [
                    'min' => 0,
                    'max' => 3
                ]
            ])
            ->add('defense', IntegerType::class, [
                'label' => 'Défense',
                'attr' => [
                    'min' => 0,
                    'max' => 3
                ]
            ])
            ->add('enregistrer', SubmitType::class, [
                'label' => "Etape suivante"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([

            'data_class' => Combat::class,
            'constraints' => [new TotalEquals(4)],
        ]);
    }
}
