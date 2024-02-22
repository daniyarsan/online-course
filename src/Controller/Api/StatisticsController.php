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
use App\Services\RankService;
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
 * @Route("/statistics", name="api_statistics_")
 */
class StatisticsController extends AbstractController
{
    use ExceptionHandlerTrait;

    private $responseService;
    private $manager;

    public function __construct(ResponseService $responseService, EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->responseService = $responseService;
    }

    /**
     * @Route("", name="list", methods={"GET"})
     */
    public function index(QuizRepository $repository, QuizRepository $quizRepository, RankService $rankService): JsonResponse
    {
        /* @var $currentUser User */
        $currentUser = $this->getUser();
        $results = $currentUser->getResults();

        $maxPossibleScores = array_map(function (Quiz $quiz) {
            return $quiz->getMaxPossibleScore();
        }, $quizRepository->findAll());

        /*TODO: Experimental ranking */
        $successPercentage = ($currentUser->getTotalScore() * 100) / array_sum($maxPossibleScores);
        return $this->responseService->getResponse([
            'rank' => $rankService->getRankByScorePercentage($successPercentage),
            'countResults' => count($results),
            'scoreTotal' => $currentUser->getTotalScore(),
            'results' => $results,
            'userRegistered' => $currentUser->getCreatedAt()
        ], 'user');
    }


    /**
     * @Route("/{id}", name="one", methods={"GET"})
     */
    public function one($id, QuizRepository $repository): JsonResponse
    {
        return $this->responseService->getResponse($repository->findOneBy(['id' => $id]), 'user');
    }


}
