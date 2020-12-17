<?php

namespace App\Form;

use App\Entity\Syntax;
use App\Entity\Trade;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SyntaxType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration('Syntaxe', "Saisir la syntaxe"))
            ->add('description', TextType::class, $this->getConfiguration('Description', "Saisir la descriptiuon de la syntaxe"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Syntax::class,
        ]);
    }
}
