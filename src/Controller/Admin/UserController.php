<?php

namespace App\Controller\Admin;

use App\Business\DTO\RegistrationDto;
use App\Business\UserCreator;
use App\Entity\User;
use App\Form\SearchForms\UserSearchType;
use App\Repository\UserRepository;
use App\Services\ResponseService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/users", name="admin_users_")
 */
class UserController extends AbstractController
{

    private $responseService;

    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
    }

    /**
     * @Route("", name="index", methods={"GET"})
     */
    public function index(Request $request, UserRepository $repository): Response
    {
        $searchForm = $this->createForm(UserSearchType::class);
        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $data = $searchForm->getData();
            $result = $repository->getListByFilter($data);
        } else {
            $result = $repository->getListByFilter([]);
        }

        return $this->render('admin/users/index.html.twig', [
            'data' => $result,
            'searchForm' => $searchForm->createView()
        ]);
    }


    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show($id, Request $request, UserRepository $userRepository): Response
    {
        $entity = $userRepository->findOneBy(['id' => $id]);
        if (!$entity) {
            throw new EntityNotFoundException('No user specified');
        }

        return $this->render('admin/users/show.html.twig', [
            'entity' => $entity
        ]);
    }


    /**
     * @Route("/delete/{id}", name="delete", methods={"POST"})
     * @ParamConverter ("user", class="App\Entity\User")
     */
    public function delete($user, EntityManagerInterface $manager): RedirectResponse
    {
        if (!$user) {
            throw new EntityNotFoundException('User not found');
        }

        $this->addFlash('success', 'Пользователь удален успешно');
        $manager->remove($user);
        $manager->flush();

        return $this->redirectToRoute('admin_users_index');

    }


    /**
     * @Route("/{id}", name="update", methods={"PUT"})
     * @ParamConverter ("user", class="App\Entity\User")
     */
    public function update($user, Request $request, SerializerInterface $serializer, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): JsonResponse
    {
        $user = $serializer
            ->deserialize(
                $request->getContent(),
                User::class,
                'json',
                [
                    AbstractNormalizer::OBJECT_TO_POPULATE => $user,
                    AbstractNormalizer::IGNORED_ATTRIBUTES => ['department']
                ]
            );

        if (!$user) {
            return new JsonResponse([
                'message' => 'Something has gone wrong',
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($user->getPlainPassword()) {
            $user->setPassword($hasher->hashPassword($user, $user->getPlainPassword()));
        }

        $manager->persist($user);
        $manager->flush();

        return $this->responseService->getResponse($user);
    }

    /**
     * @Route("/create", name="create", methods={"POST"})
     */
    public function create(
        Request             $request,
        SerializerInterface $serializer,
        UserRepository      $repository,
        UserCreator         $userCreator): JsonResponse
    {
        $dto = $serializer->deserialize(
            $request->getContent(),
            RegistrationDto::class,
            'json'
        );

        $user = $repository->findOneBy(['username' => $dto->getUsername()]);
        if ($user) {
            return new JsonResponse([
                'message' => 'User already exists',
            ], Response::HTTP_BAD_REQUEST);
        }

        $newUser = $userCreator->create($dto);

        return new JsonResponse([
            'message' => 'Пользователь создан успешно',
            'userId' => $newUser->getId(),
        ], Response::HTTP_CREATED);
    }


    /**
     * @Route("", name="delete_all", methods={"DELETE"})
     */
    public function deleteAll(
        Request                $request,
        UserRepository         $repository,
        EntityManagerInterface $manager
    )
    {
        $idsToRemove = json_decode($request->getContent(), true);
        $entities = $repository->findBy($idsToRemove);

        foreach ($entities as $entity) {
            $manager->remove($entity);
        }
        $manager->flush();

        return $this->json(
            $idsToRemove,
            Response::HTTP_OK,
            []
        );
    }


    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_users_delete', ['id' => $id]))
            ->setMethod('DELETE')
            ->add('submit', 'submit', ['label' => 'Delete'])
            ->getForm();
    }


}
