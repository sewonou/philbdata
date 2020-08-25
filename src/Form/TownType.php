<?php

namespace App\Form;

use App\Entity\Region;
use App\Entity\Town;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TownType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, $this->getConfiguration('Dénommination', "Saisir la dénommination de la Commune"))
            ->add('region',EntityType::class, $this->getConfiguration("Région", "Choisir la région d'appartenance", [
                'class' => Region::class,
                'choice_label' => 'name',
                'placeholder' => "Choisir la région d'appartenance ..."
            ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Town::class,
        ]);
    }
}
