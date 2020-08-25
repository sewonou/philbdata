<?php

namespace App\Form;

use App\Entity\Prefecture;
use App\Entity\Township;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TownshipType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, $this->getConfiguration('Dénommination', "Sasir la dénommination de la préfecture"))
            ->add('prefecture',EntityType::class, $this->getConfiguration("Préfecture", "Choisir la préfecture d'appartenance", [
                'class' => Prefecture::class,
                'choice_label' => 'name',
                'placeholder' => "Choisir la préfecture d'appartenance ...",
            ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Township::class,
        ]);
    }
}
