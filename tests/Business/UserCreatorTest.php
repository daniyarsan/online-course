<?php

namespace App\Tests\Business;

use App\Business\UserCreator;
use App\DataFixtures\DepartmentFixtures;
use App\DataFixtures\QuestionFixtures;
use App\DataFixtures\UserFixtures;
use App\Command\RegistrationDto;
use App\Entity\Department;
use App\Entity\User;
use App\Repository\DepartmentRepository;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class UserCreatorTest extends KernelTestCase
{

//    protected function setUp():void
//    {
//        $loader = new Loader();
//        $loader->loadFromDirectory('./src/DataFixtures');
//
//        $loader = new Loader();
//        $loader->addFixture(new DepartmentFixtures());
//        $loader->addFixture(new QuestionFixtures());
//        $loader->addFixture(new UserFixtures());
//
//        $purger = new ORMPurger($this->getEntityManager());
//        $executor = new ORMExecutor($this->getEntityManager(), $purger);
//        $executor->execute($loader->getFixtures());
//
//        parent::setUp();
//
//    }
//
//
//    /**
//     * @dataProvider userData
//     */
//    public function testUserCreate($username, $departmentName, $password): void
//    {
//
//        $passwordHasherFactory = new PasswordHasherFactory([
//            PasswordAuthenticatedUserInterface::class => ['algorithm' => 'auto'],
//        ]);
//        $encoder = new UserPasswordHasher($passwordHasherFactory);
//        $entityManager = $this->getEntityManager();
//
//        $userCreator = new UserCreator($entityManager, $encoder);
//
//        $departmentEntity = static::getContainer()->get('doctrine')->getRepository(Department::class)->findOneBy(['name' => $departmentName]);
//
//        $registrationDto = new RegistrationDto();
//        $registrationDto->setUsername($username);
//        $registrationDto->setDepartment($departmentEntity->getId());
//        $registrationDto->setPlainPassword($password);
//
//        $user = $userCreator->create($registrationDto);
//
//        $this->assertSame($username, $user->getUsername());
//        $this->assertSame($departmentEntity->getId(), $user->getDepartment()->getId());
//    }
//
//    public function userData()
//    {
//        return [
//            ['testuser', Department::DEVOPS, '123123123'],
//            ['testuser', Department::TESTER, '123123123']
//        ];
//    }
//
//
//    private function getEntityManager() {
//        return static::getContainer()->get('doctrine')->getManager();
//    }
//
//    public function tearDown():void
//    {
//        $purger = new ORMPurger($this->getEntityManager());
//        $purger->purge();
//    }
}
