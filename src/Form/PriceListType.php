<?php

namespace App\Form;

use App\Entity\PriceList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriceListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('minRange')
            ->add('maxRange')
            ->add('minPrice')
            ->add('maxPrice')
            ->add('commission')
            ->add('posCommission')
            ->add('dealerCommission')
            ->add('updateAt')
            ->add('author')
            ->add('priceCategory')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PriceList::class,
        ]);
    }
}
