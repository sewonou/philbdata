<?php

namespace App\Form;

use App\Entity\PriceCategory;
use App\Entity\PriceList;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriceListType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('minRange', TextType::class, $this->getConfiguration('Tranche Min', 'Tranche minimum'))
            ->add('maxRange', TextType::class, $this->getConfiguration('Tranche Max', 'Tranche Maximum'))
            ->add('minPrice', TextType::class, $this->getConfiguration('Prix min', 'Prix minimum'))
            ->add('maxPrice', TextType::class, $this->getConfiguration('Prix max', 'Prix maximum'))
            ->add('commission', TextType::class, $this->getConfiguration('Commission réseau', 'Commission du réseau'))
            ->add('posCommission', TextType::class, $this->getConfiguration('Commission PDV', 'Commission PDV'))
            ->add('dealerCommission', TextType::class, $this->getConfiguration('Commission Dealer', 'Commission dealer'))
            ->add('priceCategory', EntityType::class, $this->getConfiguration('Catégorie','', [
                'placeholder' => "Choisir la catégorie",
                'class'  => PriceCategory::class,
                'choice_label' => 'title'
            ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PriceList::class,
        ]);
    }
}
