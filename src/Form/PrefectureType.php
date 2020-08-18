<?php

namespace App\Form;

use App\Entity\Prefecture;
use App\Entity\Town;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrefectureType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, $this->getConfiguration('Dénommination', "Saisir la dénommination de la préfecture"))
            ->add('town',EntityType::class, $this->getConfiguration("Commune", "Choisir la commune d'appartenance", [
                'class' => Town::class,
                'choice_label' => 'name',
            ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Prefecture::class,
        ]);
    }
}
