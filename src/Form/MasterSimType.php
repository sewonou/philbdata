<?php

namespace App\Form;

use App\Entity\MasterSim;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MasterSimType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('msisdn', TextType::class, $this->getConfiguration('MSISDN', "Saisir le numéro MSISDN"))
            ->add('name', TextType::class, $this->getConfiguration('Désignation', "Saisir la désignation du numéro"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MasterSim::class,
        ]);
    }
}
