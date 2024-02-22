<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="api_index_")
 */
class HealthcheckController extends AbstractController
{

    /**
     * @Route("", name="healthcheck")
     */
    public function index(): Response
    {

        return $this->json(['status' => 'ok']);
    }
}
