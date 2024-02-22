<?php

namespace App\Controller\Admin;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Form\QuestionType;
use App\Form\QuizType;
use App\Form\SearchForms\QuizSearchType;
use App\Repository\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/quiz", name="admin_quiz")
 */
class QuizController extends AbstractController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("", name="_index", methods={"GET"})
     */
    public function index(Request $request, QuizRepository $repository): Response
    {
        $searchForm = $this->createForm(QuizSearchType::class);
        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $data = $searchForm->getData();
            $result = $repository->getListByFilter($data);
        } else {
            $result = $repository->getListByFilter([]);
        }

        return $this->render('admin/quiz/index.html.twig', [
            'data' => $result,
            'searchForm' => $searchForm->createView()
        ]);
    }

    /**
     * @Route("/create", name="_create")
     */
    public function create(Request $request): Response
    {
        $quiz = new Quiz();
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            dd($quiz->getDepartments());

            $this->entityManager->persist($quiz);
            $this->entityManager->flush();
//            $this->service->saveData($form, $doctrine);

            return $this->redirect($this->generateUrl('admin_quiz_edit', ['id' => $quiz->getId()]));
        }

        return $this->render('admin/quiz/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="_edit")
     * @ParamConverter ("quiz", class="App\Entity\Quiz")
     */
    public function edit($quiz, Request $request): Response
    {
        if (!$quiz) {
            throw new EntityNotFoundException('Нет теста с данным идентификатором');
        }

        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($quiz);
            $this->entityManager->flush();
//            $this->service->saveData($form, $doctrine);

            return $this->redirect($this->generateUrl('admin_quiz_edit', ['id' => $quiz->getId()]));
        }


        return $this->render('admin/quiz/edit.html.twig', [
            'form' => $form->createView(),
            'quiz' => $quiz
        ]);
    }

    /**
     * @Route("/delete/{id}", name="_delete", methods={"POST"})
     * @ParamConverter ("quiz", class="App\Entity\Quiz")
     */
    public function delete($quiz, EntityManagerInterface $manager): RedirectResponse
    {
        if (!$quiz) {
            throw new EntityNotFoundException('quiz not found');
        }

        $this->entityManager->remove($quiz);
        $this->entityManager->flush();

        $this->addFlash('success', 'Тест удален успешно');
        return $this->redirectToRoute('admin_quiz_index');

    }

    /**
     * @Route("/{id}/question/create", name="_question_create")
     * @ParamConverter ("quiz", class="App\Entity\Quiz")
     *
     */
    public function createQuestion(Request $request, $quiz): Response
    {
        $questionObject = new Question();
        $form = $this->createForm(QuestionType::class, $questionObject);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $questionObject->setQuiz($quiz);
            $quiz->addQuestion($questionObject);

            $this->entityManager->persist($questionObject);
            $this->entityManager->persist($quiz);
            $this->entityManager->flush();

            return $this->redirect($this->generateUrl('admin_quiz_edit', ['id' => $quiz->getId()]));

        } else {

            foreach ($form->getErrors() as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }

        return $this->render('admin/question/create.html.twig', [
            'quiz' => $quiz,
            'form' => $form->createView()
        ]);
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

