<?php

namespace App\Business;

use App\Entity\Course;
use App\Services\AvatarService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CourseManager
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

    public function getCoursesList(): array
    {

    }
}