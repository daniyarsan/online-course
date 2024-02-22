<?php

namespace App\Controller\Api;

use App\Repository\DepartmentRepository;
use App\Services\ResponseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



/**
 * @Route("/departments", name="api_departments_")
 */
class DepartmentController extends AbstractController
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
     * @Route("", name="list")
     */
    public function index(Request $request, DepartmentRepository $repository): JsonResponse
    {
        $listQuery = $repository->getListQuery($request);
        return $this->responseService->getResponse($listQuery->getQuery()->getResult(), 'user');
    }


}
