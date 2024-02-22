<?php

namespace App\Business;

use App\Business\DTO\RegistrationDto;
use App\Entity\Department;
use App\Entity\User;
use App\Exception\EntityAlreadyExistsException;
use App\Services\AvatarService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Util\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCreator
{
    private UserPasswordHasherInterface $encoder;
    private EntityManagerInterface $entityManager;
    private AvatarService $avatarService;

    public function __construct(
        EntityManagerInterface      $entityManager,
        UserPasswordHasherInterface $encoder,
        AvatarService $avatarService
    )
    {
        $this->entityManager = $entityManager;
        $this->encoder = $encoder;
        $this->avatarService = $avatarService;
    }

    public function create(RegistrationDto $dto)
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $dto->getUsername()]);
        if ($user) {
            throw new EntityAlreadyExistsException('User already exists');
        }

        $user = new User();
        $user->setUsername($dto->getUsername());
        $user->setPlainPassword($dto->getPlainPassword());
        $user->setPassword($this->encoder->hashPassword($user, $dto->getPlainPassword()));

        $department = $this->entityManager->getRepository(Department::class)->findOneBy(['id' => $dto->getDepartment()]);
        $user->setDepartment($department);
        $avatar = $this->avatarService->generate($user->getUsername());
        $user->setAvatar($avatar);

        $this->entityManager->persist($user);
        $this->entityManager->flush();


        return $user;
    }
}