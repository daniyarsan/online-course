<?php

namespace App\Controller\Api;

use App\Command\RegistrationDto;
use App\Entity\Message;
use App\Services\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/contact", name="api_contact_")
 */
class ContactController extends AbstractController
{
    /**
     * @Route("", name="index", methods={"POST"})
     */
    public function index(Request $request, MailerService $mailerService, SerializerInterface $serializer, EntityManagerInterface $manager): JsonResponse
    {
        $message = $serializer->deserialize(
            $request->getContent(),
            Message::class,
            'json'
        );

        $message->setUser($this->getUser());
        $manager->persist($message);
        $manager->flush();

//        $mailerService->sendContactMessage($message);

        return $this->json(
            ['status' => 'Created'],
            Response::HTTP_CREATED,
            [],
            []
        );
    }

    /**
     * @Route("/anon", name="anon", methods={"POST"})
     */
    public function anon(Request $request, MailerService $mailerService, SerializerInterface $serializer, EntityManagerInterface $manager)
    {
        $message = $serializer->deserialize(
            $request->getContent(),
            Message::class,
            'json'
        );

//        $mailerService->sendAnonContactMessage($message);

        $manager->persist($message);
        $manager->flush();

        return $this->json(
            ['status' => 'Created'],
            Response::HTTP_CREATED,
            [],
            []
        );

    }
}
