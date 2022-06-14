<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {

        $emails = ["mauris@mauris.com", "aptent@corporation.com", "ultricies@associates.com", "sem@elescas.com"];

        foreach ($emails as $email )
        {
            $user = new user();
            $user->setEmail($email);

            $password = $this->hasher->hashPassword($user, 'Epsi2022!');
            $user->setPassword($password);
            $manager->persist($user);
        }


        // $manager->persist($product);
        $manager->flush();
    }
}