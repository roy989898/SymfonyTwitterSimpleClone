<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);


        $superAdmin = new User();
        $superAdmin->setEmail('superadmin@fake.com');
        $superAdmin->setRoles(['ROLE_SUPER_ADMIN']);
        $superAdmin->setPassword($this->userPasswordHasher->hashPassword($superAdmin, 'password'));
        $superAdmin->setName("super admin");
        $superAdmin->setIsVerified(true);
        $manager->persist($superAdmin);


        $normalUser = new User();
        $normalUser->setEmail('roy989898@gmail.com');
        $normalUser->setPassword($this->userPasswordHasher->hashPassword($superAdmin, 'password'));
        $normalUser->setName("POM");
        $normalUser->setIsVerified(true);
        $manager->persist($normalUser);

        




        $manager->flush();
    }
}
