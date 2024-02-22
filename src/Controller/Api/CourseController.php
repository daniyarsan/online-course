<?php

namespace App\Controller\Api;

use App\Business\UserCourseManager;
use App\Entity\Course;
use App\Entity\User;
use App\Entity\UserCourse;
use App\Exception\EntityAlreadyExistsException;
use App\Repository\CourseRepository;
use App\Repository\ResultsRepository;
use App\Repository\UserCourseRepository;
use App\Services\ResponseService;
use App\Utility\Traits\ExceptionHandlerTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/course", name="api_course_")
 */
class CourseController extends AbstractController
{
    use ExceptionHandlerTrait;

    private $responseService;
    private $manager;
    private ResultsRepository $resultRepository;

    public function __construct(
        ResponseService $responseService,
        EntityManagerInterface $manager,
        ResultsRepository $resultRepository
    )
    {
        $this->manager = $manager;
        $this->responseService = $responseService;
        $this->resultRepository = $resultRepository;

    }

    /**
     * @Route("", name="list", methods={"GET"})
     */
    public function list(CourseRepository $repository): JsonResponse
    {
        /**@var User $currentUser */
        $currentUser = $this->getUser();
        $userCourses = $currentUser->getUserCourses();

        $courses = $repository->findAll();
        return $this->responseService->getResponse(['courses' => $courses, 'userCourses' => $userCourses], 'user');
    }

    /**
     * @Route("/started", name="started", methods={"GET"})
     */
    public function started(CourseRepository $repository): JsonResponse
    {
        /**@var User $currentUser */
        $currentUser = $this->getUser();
        $userCourses = $currentUser->getUserCourses();

        return $this->responseService->getResponse($userCourses, 'user');
    }


    /**
     * @Route("/{id}", name="one", methods={"GET"})
     * @ParamConverter ("course", class="App\Entity\Course")
     */
    public function one(Course $course, UserCourseRepository $repository): JsonResponse
    {
        /**@var User $currentUser */
        $currentUser = $this->getUser();
        $userCourse = $currentUser->getUserCourses()->filter(function (UserCourse $userCourse) use ($course) {
            return $userCourse->getCourse()->getId() === $course->getId();
        });

        try {
            if (count($userCourse) < 1) {
                throw new EntityNotFoundException('You haven\'t started course yet');
            }
        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }


        return $this->responseService->getResponse($userCourse->first(), 'user');
    }


    /**
     * @Route("/start/{id}", name="start", methods={"POST"})
     * @ParamConverter ("course", class="App\Entity\Course")
     * @throws EntityAlreadyExistsException
     */
    public function courseStart(Course $course, UserCourseManager $manager): JsonResponse
    {
        /**@var User $currentUser */
        $currentUser = $this->getUser();

        try {
            $userCourse = $manager->create($currentUser, $course);
        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }

        $this->manager->persist($userCourse);
        $this->manager->flush();

        return $this->responseService->getResponse($userCourse, 'user');
    }


//    /**
//     * @Route("/start/{id}", name="check", methods={"POST"})
//     * @ParamConverter ("course", class="App\Entity\Course")
//     */
//    public function checkCourse(Course $course, Request $request)
//    {
//        /**@var User $currentUser */
//        $currentUser = $this->getUser();
//
//        return $this->responseService->getResponse($user->hasCourse($course), 'user');
//    }

}
