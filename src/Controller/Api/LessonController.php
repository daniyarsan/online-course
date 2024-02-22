<?php

namespace App\Controller\Api;

use App\Entity\Lesson;
use App\Entity\LessonResult;
use App\Entity\User;
use App\Entity\UserCourse;
use App\Repository\LessonRepository;
use App\Repository\ResultsRepository;
use App\Services\ResponseService;
use App\Utility\Traits\ExceptionHandlerTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/lesson", name="api_lesson_")
 */
class LessonController extends AbstractController
{
    use ExceptionHandlerTrait;

    private $responseService;
    private $manager;
    private ResultsRepository $resultRepository;

    public function __construct(
        ResponseService        $responseService,
        EntityManagerInterface $manager,
        ResultsRepository      $resultRepository
    )
    {
        $this->manager = $manager;
        $this->responseService = $responseService;
        $this->resultRepository = $resultRepository;

    }

    /**
     * @Route("", name="list", methods={"GET"})
     */
    public function index(LessonRepository $repository): JsonResponse
    {

        $lessons = $repository->findAll();
        return $this->responseService->getResponse($lessons, 'user');
    }


    /**
     * @Route("/{id}", name="one", methods={"GET"})
     * @ParamConverter ("lesson", class="App\Entity\Lesson")
     */
    public function one(Lesson $lesson, LessonRepository $repository): JsonResponse
    {
        return $this->responseService->getResponse($repository->findOneBy(['id' => $lesson->getId()]), 'user');
    }


    /**
     * @Route("/{id}/complete", name="complete", methods={"POST"})
     * @ParamConverter ("lesson", class="App\Entity\Lesson")
     * @throws NonUniqueResultException
     */
    public function complete(Lesson $lesson, LessonRepository $lessonRepository): JsonResponse
    {
        /**@var User $currentUser */
        $currentUser = $this->getUser();

        $userCourse = $currentUser->getUserCourses()->filter(function (UserCourse $userCourse) use ($lesson) {
            return $userCourse->getCourse()->getId() == $lesson->getCourse()->getId();
        })->first();

        $passedLessonsIds = $userCourse->getLessonResults()->map(function (LessonResult $lessonResult) {
            return $lessonResult->getLesson()->getId();
        })->getValues();

        $nextLesson = $lessonRepository->findNextLesson($lesson);
        if (!$nextLesson) {
            $nextLesson = $lessonRepository->findOneBy(['course' => $userCourse->getCourse()], ['sorting'=>'ASC'], 1, 0);
        }
        $userCourse->setCurrentLesson($nextLesson);

        if (!in_array($lesson->getId(), $passedLessonsIds)) {
            $lessonResult = new LessonResult();
            $lessonResult->setLesson($lesson);
            $lessonResult->setUserCourse($userCourse);
            $userCourse->addLessonResult($lessonResult);

            $this->manager->persist($userCourse);
            $this->manager->flush();
        }

        return $this->responseService->getResponse([
            'nextLesson' => $nextLesson,
            'userCourse' => $userCourse
        ], 'user');
    }

}
