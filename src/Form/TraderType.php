<?php

namespace App\Form;

use App\Entity\Region;
use App\Entity\SimCard;
use App\Entity\Trader;
use App\Repository\SimCardRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TraderType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName',TextType::class, $this->getConfiguration('Nom & prénoms', " le nom complet du commercial"))
            ->add('pictureFile', FileType::class, $this->getConfiguration('Photo', "Choisir la photo du commercials"))
            ->add('isTrader', CheckboxType::class, $this->getConfiguration('Numéro Commercial', ''))
            ->add('isActive',CheckboxType::class, $this->getConfiguration('Actif', ''))
            ->add('msisdn', EntityType::class, $this->getConfiguration('Numéro FLOOZ', "", [
                'placeholder' => "Choisir le numéro MSISDN de l'opérateur téléphonique...",
                'class' => SimCard::class,
                'query_builder' => function(SimCardRepository $sr){
                    return $sr->createQueryBuilder('s')
                        ->innerJoin('s.profile', 'p')
                        ->where("p.title = 'CAGNT'")
                        ->andWhere('s.isActive = true')
                        ->orderBy('s.msisdn', 'ASC');
                },
                'choice_label' => 'fullName',
            ]))
            ->add('region', EntityType::class, $this->getConfiguration('La région', '', [
                'class' => Region::class,
                'choice_label' => 'name',
                'placeholder' => "Choisir la région du commercial",
            ]))
            ->add('name', TextType::class, $this->getConfiguration('Intitulé de la SIM', "Sasir l'intitulé de la carte SIM"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trader::class,
        ]);
    }
}
