<?php

namespace App\Form;

use App\Entity\MasterSim;
use App\Entity\Profile;
use App\Entity\SimCard;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SimCardType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, $this->getConfiguration('Nom de la SIM', "Saisir le nom de la SIM"))
            ->add('msisdn', TextType::class, $this->getConfiguration('Numéro MSISDN', "Saisir le numéro MSISDN MOOV précédé de l'indicatif"))
            ->add('isActive', CheckboxType::class, $this->getConfiguration('Actif', ''))
            ->add('profile', EntityType::class, $this->getConfiguration('Profil', "", [
                'class' => Profile::class,
                'choice_label' => 'title',
                'placeholder' => "Choisir le profil",
            ]))
            ->add('master', EntityType::class, $this->getConfiguration('Master', "",[
                'class' => MasterSim::class,
                'choice_label' => 'name',
                'placeholder' => "Choisir le numéro du master",
            ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SimCard::class,
        ]);
    }
}
