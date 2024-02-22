<?php

namespace App\Utility\Traits;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;

trait ExceptionHandlerTrait
{
    private TranslatorInterface $translator;

    public function __construct(
        TranslatorInterface $translator
    )
    {
        $this->translator = $translator;
    }

    public function handleException(\Exception $exception): JsonResponse
    {
        $body = ['message' => $exception->getMessage()];
        return new JsonResponse($body, Response::HTTP_BAD_REQUEST);
    }
}