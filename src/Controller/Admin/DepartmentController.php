<?php

namespace App\Controller\Admin;

use App\Entity\Answer;
use App\Entity\Department;
use App\Form\DepartmentType;
use App\Repository\DepartmentRepository;
use App\Services\ResponseService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/department", name="admin_department_")
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
     * @Route("", name="index", methods={"GET"})
     */
    public function index(Request $request, DepartmentRepository $repository): Response
    {
        $result = $repository->getListByFilter([]);

//        $form = $this->createForm(EvaluateType::class, new Answer());
        return $this->render('admin/department/index.html.twig', [
            'data' => $result,
        ]);
    }


    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $entity = new Department();
        $form = $this->createForm(DepartmentType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($entity);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('admin_department_edit', ['id' => $entity->getId()]));
        }

        return $this->render('admin/department/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @ParamConverter ("department", class="App\Entity\Department")
     */
    public function edit($department, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$department) {
            throw new EntityNotFoundException('Нет категории с данным идентификатором');
        }

        $form = $this->createForm(DepartmentType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($department);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('admin_department_edit', ['id' => $department->getId()]));
        }


        return $this->render('admin/department/edit.html.twig', [
            'form' => $form->createView(),
            'department' => $department
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @ParamConverter ("department", class="App\Entity\Department")
     */
    public function delete($department, EntityManagerInterface $manager): JsonResponse
    {
        $manager->remove($department);
        $manager->flush();

        return $this->json(
            $department->getId(),
            Response::HTTP_OK,
            []
        );
    }

    /**
     * @Route("", name="delete_all", methods={"DELETE"})
     */
    public function deleteAll(
        Request                $request,
        DepartmentRepository   $repository,
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

}
