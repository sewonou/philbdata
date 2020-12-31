<?php

namespace App\Form;

use App\Entity\District;
use App\Entity\Pointofsale;
use App\Entity\SimCard;
use App\Repository\SimCardRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PointofsaleType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('activity', TextType::class, $this->getConfiguration('Activité du PDV', "Saisir l'activité du PDV"))
            ->add('position', TextType::class, $this->getConfiguration('Position du PDV', "Saisir le point de repérage du PDV"))
            ->add('longitude', TextType::class, $this->getConfiguration('Longitude', "Saisir le GPS : Longitude"))
            ->add('latitude', TextType::class, $this->getConfiguration('Latitude', "Saisir le GPS : Latitude"))
            ->add('contact', TextType::class, $this->getConfiguration('Autre contact', "Saisir le contact d PDV"))
            ->add('isActive', CheckboxType::class, $this->getConfiguration('Activer', ""))
            ->add('msisdn', EntityType::class, $this->getConfiguration('', "", [
                'placeholder' => "Choisir le numéro MSISDN de l'opérateur téléphonique...",
                'class' => SimCard::class,
                'query_builder' => function(SimCardRepository $sr){
                    return $sr->createQueryBuilder('s')
                        ->innerJoin('s.profile', 'p')
                        ->where("p.title != 'CAGNT'")
                        ->andWhere("p.title != 'DLER'")
                        ->andWhere('s.isActive = true')
                        ->orderBy('s.msisdn', 'ASC');
                },
                'choice_label' => 'fullName',
            ]))
            ->add('district', EntityType::class, $this->getConfiguration('Quartier', "", [
                'placeholder' => "Choisir le quartier ou se localise le PDV",
                'class'  => District::class,
                'choice_label' => 'name'
            ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pointofsale::class,
        ]);
    }
}
