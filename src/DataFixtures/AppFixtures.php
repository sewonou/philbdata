<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
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
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN')
            ->setDescription('Administrateur');

        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser
            ->setFirstName('Sewonou')
            ->setLastName('LOKOSSOU')
            ->setLogin('sewonou')
            ->setOffice('Développeur')
            ->setPassword($this->encoder->encodePassword($adminUser, 'password'))
            ->addUserRole($adminRole)
        ;

        $manager->persist($adminUser);

        $manager->flush();
    }
}
