<?php

namespace App\DataFixtures;

use App\Entity\Department;
use App\Entity\Choice;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DepartmentFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        foreach ($this->getDepartmentList() as $departmentItem) {
            $department = new Department();
            $department->setName($departmentItem);
            $manager->persist($department);
        }
        $manager->flush();
    }

    private function getDepartmentList()
    {
        return [
            Department::MODERATOR,
            Department::SECURITY,
            Department::DEVELOPER,
            Department::TESTER,
            Department::DEVOPS,
            Department::PR
        ];
    }

}

