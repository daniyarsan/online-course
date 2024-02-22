<?php

namespace App\Controller;

use App\Business\UserCreator;
use App\Business\DTO\RegistrationDto;
use App\Services\ResponseService;
use App\Utility\Traits\ExceptionHandlerTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;


class AuthController extends AbstractController
{
    use ExceptionHandlerTrait;

    private $manager;
    private $responseService;

    public function __construct(EntityManagerInterface $manager, ResponseService $responseService)
    {
        $this->manager = $manager;
        $this->responseService = $responseService;
    }

    /**
     * @Route("/registration", name="app_registration", methods={"POST"})
     */
    public function registration(
        Request             $request,
        SerializerInterface $serializer,
        UserCreator         $userCreator): JsonResponse
    {

        $registrationDto = $serializer->deserialize(
            $request->getContent(),
            RegistrationDto::class,
            'json'
        );

        try {
            $newUser = $userCreator->create($registrationDto);
        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }


        return new JsonResponse([
            'message' => 'Пользователь создан успешно',
            'userId' => $newUser->getId(),
        ], Response::HTTP_CREATED);

//        return $this->responseService->getResponse($result, 'user');
    }
}



//if ($user) {
//    $isValidPassword = $passwordHasher->isPasswordValid($user, $dto->getPlainPassword() ? $dto->getPlainPassword() : false);
//    if ($isValidPassword) {
//        return new JsonResponse([
//            'message' => 'Auth succeed',
//            'token' => $JWTTokenManager->create($user)
//        ], Response::HTTP_CREATED);
//    }
//
//    return $this->json(
//        ['message' => 'Incorrect email or password'],
//        Response::HTTP_BAD_REQUEST
//    );
//}
