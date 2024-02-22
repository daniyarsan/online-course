<?php

namespace App\Controller\Admin;

use App\Entity\Message;
use App\Repository\MessageRepository;
use App\Repository\QuizRepository;
use App\Services\ResponseService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/message", name="admin_message")
 */
class MessageController extends AbstractController
{

    /**
     * @Route("", name="_index", methods={"GET"})
     */
    public function index(Request $request, MessageRepository $messageRepository): Response
    {
        return $this->render('admin/message/index.html.twig', [
            'data' => $messageRepository->getListByFilter([])
        ]);
    }

    /**
     * @Route("/{id}", name="_show", methods={"GET"})
     * @ParamConverter ("quiz", class="App\Entity\Message")
     */
    public function show(Message $message, Request $request): Response
    {

        return $this->render('admin/message/show.html.twig', [
            'message' => $message
        ]);
    }

}