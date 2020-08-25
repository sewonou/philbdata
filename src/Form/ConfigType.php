<?php

namespace App\Form;

use App\Entity\Config;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('configAt', DateType::class, $this->getConfiguration('Date des transactions', "", [
                'widget' => 'single_text',
                'placeholder' => 'La date des transactions.'
            ]))
            ->add('content', TextType::class, $this->getConfiguration('Contenu', "Saisir le titre du contenu du fichier"))
            ->add('files', CollectionType::class, [
                'entry_type' => ConfigFileType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Config::class,
        ]);
    }
}
