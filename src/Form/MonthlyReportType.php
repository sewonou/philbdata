<?php

namespace App\Form;

use App\Entity\MonthlyReport;
use App\Entity\SimCard;
use App\Repository\SimCardRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MonthlyReportType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('msisdn',  EntityType::class, $this->getConfiguration('', "", [
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
            ->add('depositCount', TextType::class, $this->getConfiguration('Volume des dépôts', "Saisir le volume des dépôts"))
            ->add('depositValue', TextType::class, $this->getConfiguration('Valeurs des dépôts', "Saisir la valeur des dépôts"))
            ->add('withdrawalCount', TextType::class, $this->getConfiguration('Volume des retraits', "Saisir le volume des retraits"))
            ->add('withdrawalValue', TextType::class, $this->getConfiguration('Valeur des retrais', "Saisir la valeur des retraits"))
            ->add('posCommission', TextType::class, $this->getConfiguration('Commission des PDV', "Saisir la commission des PDV"))
            ->add('dealerCommission', TextType::class, $this->getConfiguration('Commission du dealer', "Sasir la commission du dealer"))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MonthlyReport::class,
        ]);
    }
}
