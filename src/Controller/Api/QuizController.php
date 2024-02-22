<?php

namespace App\Controller\Api;

use App\Business\AnswerCreator;
use App\Business\DTO\AnswerDto;
use App\Business\DTO\ResultDto;
use App\Business\ResultCreator;
use App\Entity\Quiz;
use App\Entity\Result;
use App\Entity\User;
use App\Exception\EntityAlreadyExistsException;
use App\Repository\QuizRepository;
use App\Repository\ResultsRepository;
use App\Services\ResponseService;
use App\Utility\Traits\ExceptionHandlerTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/quiz", name="api_quiz_")
 */
class QuizController extends AbstractController
{
    use ExceptionHandlerTrait;

    private $responseService;
    private $manager;
    private ResultsRepository $resultRepository;

    public function __construct(ResponseService $responseService, EntityManagerInterface $manager, ResultsRepository $resultRepository)
    {
        $this->manager = $manager;
        $this->responseService = $responseService;
        $this->resultRepository = $resultRepository;

    }

    /**
     * @Route("", name="list", methods={"GET"})
     */
    public function index(QuizRepository $repository): JsonResponse
    {
        /* @var $currentUser User */
        $currentUser = $this->getUser();
        $results  = $currentUser->getResults();
        $passedQuizIds = $results->map(function($obj){return $obj->getQuiz()->getId();})->getValues();


        $availableQuizes = $repository->getQuizCollectionByDepartmentId($currentUser->getDepartment()->getId(), $passedQuizIds);

        return $this->responseService->getResponse($availableQuizes, 'user');
    }


    /**
     * @Route("/{id}", name="one", methods={"GET"})
     * @ParamConverter ("quiz", class="App\Entity\Quiz")
     */
    public function one(Quiz $quiz, QuizRepository $repository): JsonResponse
    {
        $hasResult = $this->resultRepository->findOneBy(['user' => $this->getUser(), 'quiz' => $quiz]);

        try {
            if ($hasResult) {
                throw new EntityAlreadyExistsException('User already passed this test');
            }
        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }


        return $this->responseService->getResponse($repository->findOneBy(['id' => $quiz->getId()]), 'user');
    }


    /**
     * @Route("/{id}", name="save", methods={"POST"})
     * @ParamConverter ("quiz", class="App\Entity\Quiz")
     * @throws EntityAlreadyExistsException
     * @throws EntityNotFoundException
     */
    public function save(Quiz $quiz, Request $request, ResultCreator $resultCreator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $resultDto = new ResultDto($data['answers'], $data['time']);

        try {
            $result = $resultCreator->create($quiz, $resultDto, $this->getUser());
        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }


        return $this->responseService->getResponse($result, 'user');
    }

}
