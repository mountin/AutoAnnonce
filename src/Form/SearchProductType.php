<?php
/**
 * Created by PhpStorm.
 * User: mountin
 * Date: 1/9/25
 * Time: 9:46 AM
 */
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'label' => 'Product Name',
                'attr' => ['placeholder' => 'Enter product name'],
            ])
            ->add('brand', TextType::class, [
                'required' => false,
                'label' => 'Category',
                'attr' => ['placeholder' => 'Enter brand'],
            ])

            ->add('from', NumberType::class, [
                'required' => false,
                'label' => 'Price From',
                'attr' => [
                    'placeholder' => 'Minimum price',
                ],
            ])
            ->add('till', NumberType::class, [
                'required' => false,
                'label' => 'Price Till',
                'attr' => [
                    'placeholder' => 'Maximum price',
                ],
            ])

            ->add('search', SubmitType::class, [
                'label' => 'Search',
            ]);

    }
}