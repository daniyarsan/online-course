<?php

namespace App\Controller\Admin;

use App\Repository\QuizRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="admin_dashboard")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("", name="_index", methods={"GET"})
     */
    public function index(QuizRepository $repository)
    {
        return $this->render('admin/dashboard/index.html.twig', []);
    }
}