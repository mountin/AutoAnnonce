<?php

namespace App\Form;

use App\Entity\Brands;
use App\Entity\CarType;
use App\Entity\Cars;
use App\Entity\Options;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\File;


class CarsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('address')
            ->add('description')
            ->add('price')
            ->add('year')
            ->add('fuel', ChoiceType::class, [
                'choices' => ['Essence' => 'Essence', 'Diesel'=>'Diesel', 'Gas'=>'Gas'],
                'placeholder' => 'Choose a fuel',
                'required' => false,
            ])
//            ->add('extraField', TextType::class, [
//                'mapped' => false
//            ])
//            ->add('user', EntityType::class, [
//                'class' => User::class,
//                'choice_label' => 'email',
//                'attr' => [
//                    'class' => 'custom-select', // Add a CSS class
//                    'style' => 'width:10%!important'
//                ]
//            ])
            ->add('brand', EntityType::class, [
                'class' => Brands::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'custom-select',
                    'style' => 'width:10%!important'
                    ]
            ])
            ->add('type', EntityType::class, [
                'class' => CarType::class,
                'choice_label' => 'name',
            ])
            ->add('options', EntityType::class, [
                'class' => Options::class,
                'choice_label' => 'id',
                'mapped' => false,
                'required' => false,
                'multiple' => true, // Allows selecting multiple groups
                'expanded' => true,  // Render checkboxes for the user to select multiple groups

            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Auto Image (JPEG/PNG file)',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image (JPEG, PNG, GIF)',
                    ]),
                ],
            ])

            ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cars::class,
        ]);
    }
}
