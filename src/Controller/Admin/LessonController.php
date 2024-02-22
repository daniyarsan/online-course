<?php

namespace App\Controller\Admin;

use App\Business\ChapterManager;
use App\Business\MediaManager;
use App\Entity\Course;
use App\Entity\Lesson;
use App\Entity\MediaFile;
use App\Exception\FileNotFoundException;
use App\Form\LessonType;
use App\Repository\CourseRepository;
use App\Repository\LessonRepository;
use App\Services\AsyncUploader\AsyncUploader;
use App\Utility\Traits\ExceptionHandlerTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/lesson", name="admin_lesson")
 */
class LessonController extends AbstractController
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
    public function index(Request $request, CourseRepository $courseRepository): Response
    {
        return $this->json(['status' => 'ok']);
    }

    /**
     * @Route("/create/course/{id}", name="_create")
     * @ParamConverter ("course", class="App\Entity\Course")
     */
    public function createLesson(
        Course           $course,
        Request          $request,
        ChapterManager   $chapterManager,
        LessonRepository $lessonRepository
    ): Response
    {
        $form = $this->createForm(LessonType::class, new Lesson(), ['course' => $course]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**@var Lesson $lesson */
            $lesson = $form->getData();

            if (!$lesson->getChapter()) {
                $chapter = $chapterManager->create($form->get('chapterTitle')->getData(), $course);
                $lesson->setChapter($chapter);
            }

            $lesson->setCourse($course);

            $latestLesson = $lessonRepository->findOneBy(['course' => $course], ['sorting' => 'DESC'], 1, 0);

            $lesson->setSorting(1);
            if ($latestLesson) {
                $lesson->setSorting($latestLesson->getSorting() + 1);
            }

            $this->entityManager->persist($lesson);
            $this->entityManager->flush();

            $this->addFlash('success', 'Создано успешно');

            return $this->redirect($this->generateUrl('admin_lesson_edit', ['id' => $lesson->getId()]));
        } else {
            foreach ($form->getErrors() as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }

        return $this->render('admin/lesson/create.html.twig', [
            'course' => $course,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="_edit")
     * @ParamConverter ("lesson", class="App\Entity\Lesson")
     */
    public function edit(
        Lesson         $lesson,
        Request        $request,
        MediaManager   $mediaManager,
        ChapterManager $chapterManager
    ): Response
    {
        if (!$lesson) {
            throw new EntityNotFoundException('Нет теста с данным идентификатором');
        }
        $course = $lesson->getCourse();
        $form = $this->createForm(LessonType::class, $lesson, ['course' => $course]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**@var Lesson $lesson */
            $lesson = $form->getData();

            if (!$lesson->getChapter()) {
                $chapter = $chapterManager->create($form->get('chapterTitle')->getData(), $course);
                $lesson->setChapter($chapter);
            }

            $mediaManager->hydrateMedia($lesson);

            $this->entityManager->persist($lesson);
            $this->entityManager->flush();

            $this->addFlash('success', 'Сохранено успешно');

            return $this->redirect($this->generateUrl('admin_lesson_edit', ['id' => $lesson->getId()]));
        } else {
            foreach ($form->getErrors() as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }

        return $this->render('admin/lesson/edit.html.twig', [
            'course' => $course,
            'lesson' => $lesson,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="_delete", methods={"POST"})
     * @ParamConverter ("lesson", class="App\Entity\Lesson")
     */
    public function delete(Lesson $lesson, EntityManagerInterface $manager): RedirectResponse
    {
        if (!$lesson) {
            throw new EntityNotFoundException('Course not found');
        }

        $courseId = $lesson->getCourse()->getId();

        $this->entityManager->remove($lesson);
        $this->entityManager->flush();

        $this->addFlash('success', 'Удалено успешно');
        return $this->redirectToRoute('admin_course_edit', ['id' => $courseId]);

    }

    /**
     * @Route("/{id}/upload", name="_fileupload")
     * @ParamConverter ("course", class="App\Entity\Lesson")
     * @throws \Exception
     */
    public function fileUpload(
        Lesson        $lesson,
        Request       $request,
        AsyncUploader $asyncUploader
    ): Response
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            header('Access-Control-Allow-Origin: *');
            header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
            header("HTTP/1.1 200 OK");
            die();
        }

        /**@var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('file');
        $fileData = $request->request->all();

        $response = [];

        try {
            $filePath = $asyncUploader->process($uploadedFile, $fileData['name'], $fileData['chunk'], $fileData['chunks']);
            if ($filePath) {
                $response['file'] = $filePath;

                $mediaFile = new MediaFile();
                $mediaFile->setLesson($lesson);
                $mediaFile->setType(pathinfo($fileData['name'], PATHINFO_EXTENSION));
                $mediaFile->setPath($filePath);
                $mediaFile->setUid($fileData['uid']);

                $this->entityManager->persist($mediaFile);
                $this->entityManager->flush();
                $response['id'] = $mediaFile->getId();
            }
        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }

        return $this->json(array_merge($response, ["status" => 'ok']));
    }

    /**
     * @Route("/file/{uid}/delete", name="_file_delete")
     * @ParamConverter ("mediaFile", class="App\Entity\MediaFile")
     * @throws \Exception
     */
    public function fileDelete(
        MediaFile     $mediaFile,
        Request       $request,
        AsyncUploader $asyncUploader
    ): Response
    {
        if (!$mediaFile) {
            throw new FileNotFoundException();
        }
        $lesson = $mediaFile->getLesson();

        $this->entityManager->remove($mediaFile);
        $this->entityManager->flush();
        $this->addFlash('success', 'Удалено успешно');

        return $this->redirectToRoute('admin_lesson_edit', ['id' => $lesson->getId()]);
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

