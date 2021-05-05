<?php

namespace App\Form;

use App\Entity\District;
use App\Entity\Town;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DistrictType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, $this->getConfiguration('Dénommination', "Sasir la dénommination du quartier"))
            ->add('town',EntityType::class, $this->getConfiguration("Commune", "Choisir la commune d'appartenance", [
                'class' => Town::class,
                'choice_label' => 'name',
                'placeholder' => "Choisir le commune d'appartenance..."
            ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => District::class,
        ]);
    }
}
