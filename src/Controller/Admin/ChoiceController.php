<?php

namespace App\Controller\Admin;

use App\Entity\Choice;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/choice", name="admin_choice")
 */
class ChoiceController extends AbstractController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }




    /**
     * @Route("/delete/{id}", name="_delete", methods={"POST"})
     * @ParamConverter ("question", class="App\Entity\Choice")
     */
    public function delete(Choice $choice, Request $request): Response
    {
        $question = $choice->getQuestion();

        if (!$choice) {
            throw new EntityNotFoundException('Вариант ответа не найден для удаления');
        }

        $this->entityManager->remove($choice);
        $this->entityManager->flush();

        $this->addFlash('success', 'Вариант ответа удален успешно');
        return $this->redirect($this->generateUrl('admin_question_edit', ['id' => $question->getId(), 'type' => 'quiz']));
    }


}
