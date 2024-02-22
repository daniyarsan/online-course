<?php

namespace App\Business;

use App\Business\DTO\RegistrationDto;
use App\Entity\Course;
use App\Entity\User;
use App\Entity\UserCourse;
use App\Exception\EntityAlreadyExistsException;
use App\Repository\ChapterRepository;
use App\Repository\LessonRepository;
use App\Services\AvatarService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UserCourseManager
{
    private UserPasswordHasherInterface $encoder;
    private EntityManagerInterface $entityManager;
    private LessonRepository $lessonRepository;
    private ChapterRepository $chapterRepository;

    public function __construct(
        EntityManagerInterface      $entityManager,
        UserPasswordHasherInterface $encoder,
        LessonRepository $lessonRepository,
        ChapterRepository $chapterRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->encoder = $encoder;
        $this->lessonRepository = $lessonRepository;
        $this->chapterRepository = $chapterRepository;
    }

    public function create(User $user, Course $course)
    {
        if ($user->hasCourse($course)) {
            throw new EntityAlreadyExistsException('Course has already been added');
        }

        $userCourse = new UserCourse();
        $userCourse->setUser($user);
        $userCourse->setCourse($course);

        $firstLesson = $this->lessonRepository->findOneBy(['course' => $course], ['sorting'=>'ASC'], 1, 0);
        $userCourse->setCurrentLesson($firstLesson);

        return $userCourse;
    }
}