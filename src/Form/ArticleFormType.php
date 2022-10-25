<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('price')
            ->add('description')
            ->add('city')
            ->add('category')
            ->add('images', CollectionType::class, [
                'entry_type' => ImageFormType::class,
                'label' => "image",
                "allow_add" => true,
                "prototype" => true,
                // 'allow_delete' => true,
                'by_reference' => false,
                // "mapped" => false
                'prototype_options' => [
                    "attr" => [
                        "data-hello" => "abcdef",
                    ]
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
