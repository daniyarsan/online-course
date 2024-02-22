<?php

namespace App\DataFixtures;

use App\Entity\Department;
use App\Entity\Choice;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class UserFixtures extends Fixture
{


    public function load(ObjectManager $manager)
    {

        $passwordHasherFactory = new PasswordHasherFactory([
            PasswordAuthenticatedUserInterface::class => ['algorithm' => 'auto'],
        ]);
        $encoder = new UserPasswordHasher($passwordHasherFactory);

        $user = new User();
        $user->setUsername('admin');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($encoder->hashPassword($user, 'VyU^mjFd6EC5H^6q'));

        $department = $manager->getRepository(Department::class)->findOneBy(['name' => Department::MODERATOR]);
        $user->setDepartment($department);

        $manager->persist($user);
        $manager->flush();
    }

}

