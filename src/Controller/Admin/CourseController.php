<?php

namespace App\Controller\Admin;

use App\Business\ChapterManager;
use App\Business\LessonManager;
use App\Business\MediaManager;
use App\Entity\Course;
use App\Entity\Lesson;
use App\Entity\MediaFile;
use App\Form\CourseType;
use App\Form\LessonType;
use App\Form\SearchForms\CourseSearchType;
use App\Form\SearchForms\QuizSearchType;
use App\Repository\CourseRepository;
use App\Repository\LessonRepository;
use App\Services\UploaderService;
use App\Utility\Traits\ExceptionHandlerTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/course", name="admin_course")
 */
class CourseController extends AbstractController
{
    use ExceptionHandlerTrait;

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
        $searchForm = $this->createForm(CourseSearchType::class);
        $searchForm->handleRequest($request);
        $data = $searchForm->getData();

        $result = $repository->getListByFilter($data);


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
            $this->addFlash('success', 'Курс сохранен успешно');

            return $this->redirect($this->generateUrl('admin_course_edit', ['id' => $course->getId()]));
        } else {
            foreach ($form->getErrors() as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }

        return $this->render('admin/course/edit.html.twig', [
            'form' => $form->createView(),
            'course' => $course
        ]);
    }

    /**
     * @Route("/delete/{id}", name="_delete", methods={"POST"})
     * @ParamConverter ("course", class="App\Entity\Course")
     */
    public function delete($course, EntityManagerInterface $manager): RedirectResponse
    {
        if (!$course) {
            throw new EntityNotFoundException('Course not found');
        }

        $this->entityManager->remove($course);
        $this->entityManager->flush();

        $this->addFlash('success', 'Курс удален успешно');
        return $this->redirectToRoute('admin_course_index');

    }




//    /**
//     * @Route("/{id}/chapter", name="_chapter_index")
//     * @ParamConverter ("course", class="App\Entity\Course")
//     */
//    public function chapters(
//        Request $request,
//        Course $course)
//    {
//        return $this->render('admin/course/chapter/index.html.twig', [
//            'course' => $course
//        ]);
//    }
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

