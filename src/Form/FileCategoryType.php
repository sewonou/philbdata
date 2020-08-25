<?php

namespace App\Form;

use App\Entity\FileCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileCategoryType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('slug', TextType::class, $this->getConfiguration('Slug', "Saisir le slug de la catégorie"))
            ->add('title', TextType::class, $this->getConfiguration('Libellé', "Saisir le libellé de la catégorie"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FileCategory::class,
        ]);
    }
}
