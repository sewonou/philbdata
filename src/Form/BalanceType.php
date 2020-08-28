<?php

namespace App\Form;

use App\Entity\Balance;
use App\Entity\SimCard;
use App\Repository\SimCardRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BalanceType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('executionAt', DateTimeType::class, $this->getConfiguration('Date de la transaction', "", [
                'widget' => 'single_text',
                'placeholder' => 'La date des transactions.'
            ]))
            ->add('amount', TextType::class, $this->getConfiguration('Montant', "Saisir le montant de la transaction"))
            ->add('msisdn', EntityType::class, $this->getConfiguration('Point de vente', "", [
                'class' => SimCard::class,
                'placeholder' => "Choisir le numéro MSISDN de l'opérateur ..",
                'query_builder' => function(SimCardRepository $sr){
                    return $sr->createQueryBuilder('s')
                        ->innerJoin('s.profile', 'p')
                        ->andWhere('s.isActive = true')
                        ->orderBy('s.msisdn', 'ASC');
                },
                'choice_label' => 'fullName',
            ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Balance::class,
        ]);
    }
}
