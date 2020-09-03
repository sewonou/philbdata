<?php

namespace App\Form;

use App\Entity\Search;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startAt', DateType::class,
                $this->getConfiguration('Date des transactions', "", [
                    'widget' => 'single_text',
                    'placeholder' => 'Date début de la période'
                ]))
            ->add('endAt', DateType::class,
                $this->getConfiguration('Date des transactions', "", [
                    'widget' => 'single_text',
                    'placeholder' => 'Date fin de la période'
                ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'post',
            'data_class' => Search::class,
            'csrf_protection' => false,
        ]);
    }
}
