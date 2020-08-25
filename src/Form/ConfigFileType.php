<?php

namespace App\Form;

use App\Entity\ConfigFile;
use App\Entity\FileCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigFileType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, $this->getConfiguration('Fichier ', "Choisir le fichier"))
            ->add('createdAt', DateType::class, $this->getConfiguration('Date des transactions', "", [
                'widget' => 'single_text',
                'placeholder' => 'La date des transactions.'
            ]))
            ->add('category', EntityType::class, $this->getConfiguration('Région', "Choisir la région ...", [
                'class' => FileCategory::class,
                'choice_label' => 'title',
                'placeholder' => "Choisir la catégorie ..."
            ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ConfigFile::class,
        ]);
    }
}
