<?php

namespace App\Form;

use App\Entity\Control;
use App\Entity\Pointofsale;
use App\Entity\Trader;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ControlType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startAt', DateType::class, $this->getConfiguration('Date début', "", [
                'widget' => 'single_text',
                'placeholder' => 'La date des transactions.'
            ]))
            ->add('endAt', DateType::class, $this->getConfiguration('Date fin', "", [
                'widget' => 'single_text',
                'placeholder' => 'La date des transactions.'
            ]))
            ->add('isActive', CheckboxType::class, $this->getConfiguration('Activer', ""))
            ->add('trader', EntityType::class, $this->getConfiguration('Commercial', "", [
                'placeholder' => "sélectionner le commercial",
                'class' => Trader::class,
                'choice_label' => 'fullName'
            ]))
            ->add('pointofsale', EntityType::class, $this->getConfiguration('Point de vente', "", [
                'placeholder' => "sélectionner le point de vente",
                'class' => Pointofsale::class,
                'choice_label' => 'name'
            ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Control::class,
        ]);
    }
}
