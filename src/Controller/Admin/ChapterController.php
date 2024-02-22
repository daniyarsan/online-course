<?php

namespace App\Controller\Admin;

use App\Entity\Chapter;
use App\Entity\Course;
use App\Form\CourseType;
use App\Form\SearchForms\QuizSearchType;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/chapter", name="admin_chapter")
 */
class ChapterController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("", name="_index", methods={"GET"})
     */
    public function index(Request $request, CourseRepository $repository): Response
    {
        $searchForm = $this->createForm(QuizSearchType::class);
        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $data = $searchForm->getData();
            $result = $repository->getListByFilter($data);
        } else {
            $result = $repository->getListByFilter([]);
        }

        return $this->render('admin/course/index.html.twig', [
            'data' => $result,
            'searchForm' => $searchForm->createView()
        ]);
    }

    /**
     * @Route("/create", name="_create")
     */
    public function create(Request $request): Response
    {
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($course);
            $this->entityManager->flush();
//            $this->service->saveData($form, $doctrine);

            return $this->redirect($this->generateUrl('admin_course_edit', ['id' => $course->getId()]));
        }

        return $this->render('admin/course/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="_edit")
     * @ParamConverter ("course", class="App\Entity\Course")
     */
    public function edit($course, Request $request): Response
    {
        if (!$course) {
            throw new EntityNotFoundException('Нет теста с данным идентификатором');
        }

        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($course);
            $this->entityManager->flush();

            return $this->redirect($this->generateUrl('admin_course_edit', ['id' => $course->getId()]));
        }


        return $this->render('admin/course/edit.html.twig', [
            'form' => $form->createView(),
            'course' => $course
        ]);
    }

    /**
     * @Route("/delete/{id}", name="_delete", methods={"POST"})
     * @ParamConverter ("chapter", class="App\Entity\Chapter")
     */
    public function delete(Chapter $chapter, EntityManagerInterface $manager): RedirectResponse
    {
        if (!$chapter) {
            throw new EntityNotFoundException('Course not found');
        }

        $courseId = $chapter->getCourse()->getId();

        $this->entityManager->remove($chapter);
        $this->entityManager->flush();

        $this->addFlash('success', 'Удалено успешно');
        return $this->redirectToRoute('admin_course_edit', ['id' => $courseId]);

    }

    
}

//    /**
//     * @Route("", name="delete_all", methods={"DELETE"})
//     */
//    public function deleteAll(
//        Request                $request,
//        QuizRepository         $repository,
//        EntityManagerInterface $manager
//    )
//    {
//        $idsToRemove = json_decode($request->getContent(), true);
//        $entities = $repository->findBy($idsToRemove);
//
//        foreach ($entities as $entity) {
//            $manager->remove($entity);
//        }
//        $manager->flush();
//
//        return $this->json(
//            $idsToRemove,
//            Response::HTTP_OK,
//            []
//        );
//    }

