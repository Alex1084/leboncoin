<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ArticleSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('search', TextType::class,
                [
                    'required' => false
                ])
            ->add('category', EntityType::class,
                [
                    'class' => Category::class,
                    'placeholder' => "Toutes les catégories",
                    'required' => false
                ]
            )
            ->add('city', EntityType::class,
                [
                    'class' => City::class,
                    'placeholder' => "Toutes les villes",
                    'required' => false
                ]
            )
            ->add('rayon', NumberType::class,
                [
                    'required' => false
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
