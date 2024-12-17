<?php

namespace App\Form;

use App\Entity\Brands;
use App\Entity\Cars;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Enum\TypeEnum;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CarsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('address')
            ->add('description')
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
            ])
            ->add('brand', EntityType::class, [
                'class' => Brands::class,
                'choice_label' => 'name',
            ])
            ->add('type', ChoiceType::class, [
                'attr' => [
                    'class' => 'custom-select', // Add a CSS class
                    'placeholder' => 'Select Type of Vechicle...',
                    'width' =>'200px',
                    'style' => 'width:10%!important'
                ],

                'choices' => TypeEnum::cases(),
                'choice_label' => function (TypeEnum $choice) {
                    return ucfirst($choice->value);
                },
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cars::class,
        ]);
    }
}
