<?php

namespace App\DataFixtures;

use App\Entity\FileCategory;
use App\Entity\MasterSim;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        /*ROLE UTILISATEURS*/
        $adminRole = new Role();
        $superAdmin = new Role();
        $userRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN')
            ->setDescription('Administrateur');
        $superAdmin->setTitle('ROLE_SUPER_ADMIN')
            ->setDescription('Super Administrateur');
        $userRole->setTitle('ROLE_USER')
            ->setTitle('Superviseur');


        $manager->persist($adminRole);
        $manager->persist($superAdmin);
        $manager->persist($userRole);

        $adminUser = new User();
        $adminUser
            ->setFirstName('Sewonou')
            ->setLastName('LOKOSSOU')
            ->setLogin('sewonou')
            ->setOffice('Développeur')
            ->setPassword($this->encoder->encodePassword($adminUser, 'password'))
            ->addUserRole($adminRole)
            ->addUserRole($superAdmin)
        ;
        $manager->persist($adminUser);

        /*SIM MASTER*/
        $masterSim = new  MasterSim();
        $masterSim->setMsisdn('22897391919')
            ->setName('PHIL')
        ;
        $manager->persist($masterSim);


        /*CATEGORY FILE*/
        $fileCategory = new FileCategory();
        $fileCategory->setTitle('Univers des PDV/Commerciaux')
            ->setSlug('pos')
            ->setAuthor($adminUser)
        ;
        $manager->persist($fileCategory);

        $fileCategory = new FileCategory();
        $fileCategory->setTitle('Fiche des Commerciaux')
            ->setSlug('cagnt')
            ->setAuthor($adminUser)
        ;
        $manager->persist($fileCategory);

        $fileCategory = new FileCategory();
        $fileCategory->setTitle('Fiche de transaction')
            ->setSlug('transaction')
            ->setAuthor($adminUser)
        ;
        $manager->persist($fileCategory);

        $fileCategory = new FileCategory();
        $fileCategory->setTitle('Univers des POSCAGNT')
            ->setSlug('poscagnt')
            ->setAuthor($adminUser)
        ;
        $manager->persist($fileCategory);

        $fileCategory = new FileCategory();
        $fileCategory->setTitle('Fiche de transaction d\'un PDV')
            ->setSlug('postransaction')
            ->setAuthor($adminUser)

        ;
        $manager->persist($fileCategory);

        $fileCategory = new FileCategory();
        $fileCategory->setTitle('Fiche de Reporting Mensuel')
            ->setSlug('monthlyReport')
            ->setAuthor($adminUser)

        ;
        $manager->persist($fileCategory);



        $manager->flush();
    }
}
