<?php

namespace App\Form;

use App\Entity\SimCard;
use App\Entity\Trade;
use App\Entity\Type;
use App\Repository\SimCardRepository;
use App\Repository\TypeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TradeType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('refId', TextType::class, $this->getConfiguration('Reférence', "Saisir la référence"))
            ->add('amount', TextType::class, $this->getConfiguration('Montant', "Saisir le montant de la transaction"))
            ->add('transactionAt', DateTimeType::class, $this->getConfiguration('Date de la transaction', "", [
                'widget' => 'single_text',
                'placeholder' => 'La date des transactions.'
            ]))
            ->add('fromMsisdn', EntityType::class, $this->getConfiguration('Emétteur', "", [
                'class' => SimCard::class,
                'placeholder' => "Choisir le numéro MSISDN de l'émétteurr ..",
                'query_builder' => function(SimCardRepository $sr){
                    return $sr->createQueryBuilder('s')
                        ->innerJoin('s.profile', 'p')
                        ->andWhere('s.isActive = true')
                        ->orderBy('s.msisdn', 'ASC');
                },
                'choice_label' => 'fullName',
            ]))
            ->add('toMsisdn', EntityType::class, $this->getConfiguration('Receveur', "", [
                'class' => SimCard::class,
                'placeholder' => "Choisir le numéro MSISDN du receveur ..",
                'query_builder' => function(SimCardRepository $sr){
                    return $sr->createQueryBuilder('s')
                        ->innerJoin('s.profile', 'p')
                        ->andWhere('s.isActive = true')
                        ->orderBy('s.msisdn', 'ASC');
                },
                'choice_label' => 'fullName',
            ]))
            ->add('type', EntityType::class, $this->getConfiguration('Type', "", [
                'class' => Type::class,
                'placeholder' => "Choisir le type de transaction",
                'query_builder' => function(TypeRepository $tr){
                    return $tr->createQueryBuilder('t')
                        ->andWhere("t.title = 'GIVE'");
                },
                'choice_label' => 'title',
            ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trade::class,
        ]);
    }
}
