<?php

namespace App\Form;

use App\Entity\PriceCategory;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriceCategoryType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration('Titre', 'Saisire le titre de la catégorie'))
            ->add('slug', TextType::class, $this->getConfiguration('Slug', 'Saisir le slug'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PriceCategory::class,
        ]);
    }
}
