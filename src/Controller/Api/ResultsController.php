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
 * @Route("/results", name="api_results_")
 */
class ResultsController extends AbstractController
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
    public function index(QuizRepository $repository): JsonResponse
    {
        /* @var $currentUser User */
        $currentUser = $this->getUser();

        $results  = $currentUser->getResults();

        return $this->responseService->getResponse($results, 'user');
    }


    /**
     * @Route("/{id}", name="one", methods={"GET"})
     */
    public function one($id, QuizRepository $repository): JsonResponse
    {
        return $this->responseService->getResponse($repository->findOneBy(['id' => $id]), 'user');
    }


}
