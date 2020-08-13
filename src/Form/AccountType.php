<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration("Nom de l'utilisateur", "Saisir le Nom"))
            ->add('lastName', TextType::class, $this->getConfiguration("Prénom de l'utilisateur", "Saisir le Prénom"))
            ->add('login', TextType::class, $this->getConfiguration("Login de l'utilisateur", "Saisir le login"))
            ->add('password', PasswordType::class, $this->getConfiguration("Mot de passe", "Saisir le mot de passe"))
            ->add('passwordConfirm', PasswordType::class, $this->getConfiguration("Confirmation du mot de passe", "Confirmez le mot de passe"))
            ->add('office', TextType::class, $this->getConfiguration("Fonction", "Saisir la fonction"))
            ->add('pictureFile', FileType::class, $this->getConfiguration("Photo de profil", "Choisir la photo de profil"))
            ->add('userRoles', EntityType::class, $this->getConfiguration("Niveau d'accès", "Choisir le niveau d'accès ", [
                'class' => Role::class,
                'choice_label' => 'description',
                'multiple' => true,
            ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
