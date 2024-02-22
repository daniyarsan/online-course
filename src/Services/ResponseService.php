<?php

namespace App\Services;

use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use LogicException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ResponseService
{
    private const DEFAULT_PAGE = 1;
    private const DEFAULT_PAGE_SIZE = 1000;
    private const MAX_PAGE_SIZE = 250;

    /** @var PaginatorInterface */
    private $paginator;

    /** @var SerializerInterface */
    private $serializer;

    /**
     * @param PaginatorInterface $paginator
     * @param SerializerInterface $serializer
     */
    public function __construct(PaginatorInterface $paginator, SerializerInterface $serializer)
    {
        $this->paginator = $paginator;
        $this->serializer = $serializer;
    }

    public function getResponse($data, string $groups = 'admin'): JsonResponse
    {
        return $this->json($data, Response::HTTP_OK, [], ['groups' => $groups, AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true]);
    }

    public function getPaginatedResponse(Request $request, QueryBuilder $query)
    {
        return $this->getPaginationResult($request, $query);
    }

    public function getPaginatedResponseJson(Request $request, QueryBuilder $query, string $groups = 'admin'): JsonResponse
    {
        $result = $this->getPaginationResult($request, $query);
        return $this->json(
            [
                'result' => $result,
                'pagination' => [
//                    'pageSize' => $pageSize,
                    'page' => $request->query->getInt('page', self::DEFAULT_PAGE),
                    'total' => $result->getTotalItemCount(),
                ],
            ],
            Response::HTTP_OK,
            [],
            [
                'groups' => $groups,
                AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true
            ]
        );
    }
    
    private function getPaginationResult(Request $request, QueryBuilder $query): PaginationInterface
    {
        $page = $request->query->getInt('page', self::DEFAULT_PAGE);
        $pageSize = $request->query->getInt('pageSize', self::DEFAULT_PAGE_SIZE);

        try {
            $result = $this->paginator->paginate(
                $query,
                $page,
                $pageSize <= self::MAX_PAGE_SIZE ? $pageSize : self::MAX_PAGE_SIZE,
                ['wrap-queries' => true]
            );
        } catch (LogicException $exception) {
            return $this->json(['message' => $exception->getMessage(), 'code' => Response::HTTP_BAD_REQUEST]);
        }
        
        return $result;
    }

    /**
     * @param $data
     * @param int $status
     * @param array $headers
     * @param array $context
     * @return JsonResponse
     */
    private function json($data, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        $json = $this->serializer->serialize($data, 'json', array_merge([
            'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
        ], $context));

        return new JsonResponse($json, $status, $headers, true);
    }
}