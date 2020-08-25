<?php

namespace App\Form;

use App\Entity\Pointofsale;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PointofsaleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('activity')
            ->add('position')
            ->add('longitude')
            ->add('latitude')
            ->add('contact')
            ->add('isActive')
            ->add('updateAt')
            ->add('msisdn')
            ->add('district')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pointofsale::class,
        ]);
    }
}
