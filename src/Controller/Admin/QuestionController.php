<?php

namespace App\Controller\Admin;

use App\Entity\Question;
use App\Form\QuestionType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/question", name="admin_question")
 */
class QuestionController extends AbstractController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/edit/{id}", name="_edit")
     *
     */
    public function edit($id, Request $request): Response
    {
        $questionObject = $this->entityManager->getRepository(Question::class)->findOneBy(['id' => $id]);
        $form = $this->createForm(QuestionType::class, $questionObject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($questionObject);
            $this->entityManager->flush();
            return $this->redirect($this->generateUrl('admin_quiz_edit', ['id' => $questionObject->getQuiz()->getId()]));
        } else {

            foreach ($form->getErrors() as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }

        return $this->render('admin/question/edit.html.twig', [
            'question' => $questionObject,
            'form' => $form->createView(),
            'quiz' => $questionObject->getQuiz()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="_delete", methods={"POST"})
     * @ParamConverter ("question", class="App\Entity\Question")
     */
    public function delete(Question $question, Request $request): Response
    {
        $quiz = $question->getQuiz();

        if (!$question) {
            throw new EntityNotFoundException('Вопрос не найден');
        }

        $this->entityManager->remove($question);
        $this->entityManager->flush();

        $this->addFlash('success', 'Вопрос удален успешно');
        return $this->redirect($this->generateUrl('admin_quiz_edit', ['id' => $quiz->getId()]));
    }


}
