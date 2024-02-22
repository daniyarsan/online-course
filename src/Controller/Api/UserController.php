<?php

namespace App\Controller\Api;

use App\Services\ResponseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


/**
 * @Route("/user", name="api_user_")
 */
class UserController extends AbstractController
{
    /**
     * @var ResponseService
     */
    private $responseService;

    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
    }

    /**
     * @Route("/userinfo", name="userinfo", methods={"GET"})
     */
    public function userinfo(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(
                ['message' => 'User not found'],
                Response::HTTP_BAD_REQUEST,
                []
            );
        }

        return $this->responseService->getResponse($user, 'user');
    }


}
