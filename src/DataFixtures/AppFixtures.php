<?php

namespace App\DataFixtures;

use App\Entity\Post;
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
        $normalUser->setEmail('roy7676767667@gmail.com');
        $normalUser->setPassword($this->userPasswordHasher->hashPassword($superAdmin, 'password'));
        $normalUser->setName("POM");
        $normalUser->setIsVerified(true);
        $manager->persist($normalUser);


        $post1 = new Post();
        $post1->setUser($normalUser);
        $post1->setText('Post 1');

        $post2 = new Post();
        $post2->setUser($normalUser);
        $post2->setText('Post 2');
        $manager->persist($post1);
        $manager->persist($post2);


        $manager->flush();
    }
}
