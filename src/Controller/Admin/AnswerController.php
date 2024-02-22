<?php

namespace App\Controller\Admin;

use App\Entity\Answer;
use App\Form\EvaluateType;
use App\Form\SearchForms\ResultSearchType;
use App\Repository\AnswersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/answer", name="admin_answer")
 */
class AnswerController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("", name="_index", methods={"GET"})
     */
    public function index(Request $request, AnswersRepository $repository): Response
    {
        $searchForm = $this->createForm(ResultSearchType::class);
        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $data = $searchForm->getData();
            $result = $repository->getListByFilter($data);
        } else {
            $result = $repository->getListByFilter([]);
        }

        $form = $this->createForm(EvaluateType::class, new Answer());
        return $this->render('admin/answer/index.html.twig', [
            'data' => $result,
            'searchForm' => $searchForm->createView(),
            'evaluateForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/evaluate/{id}", name="_evaluate", methods={"POST"})
     * @ParamConverter ("answer", class="App\Entity\Answer")
     */
    public function evaluate(Answer $answer, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvaluateType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($answer);
            $entityManager->flush();
            $this->addFlash('success', 'Оценка выставлена успешно');
        } else {
            dd($form->getErrors());

            $this->addFlash('error', 'Произошла ошибка');
        }

        return $this->redirect($this->generateUrl('admin_answer_index'));

    }

    /**
     * @Route("/{id}", name="_show")
     * @ParamConverter ("result", class="App\Entity\Result")
     */
    public function show($result, Request $request): Response
    {
        if (!$result) {
            throw new EntityNotFoundException('Нет результата с данным идентификатором');
        }

        return $this->render('admin/result/show.html.twig', [
            'result' => $result
        ]);
    }

    /**
     * @Route("/delete/{id}", name="_delete", methods={"POST"})
     * @ParamConverter ("result", class="App\Entity\Result")
     */
    public function delete($result, EntityManagerInterface $manager): RedirectResponse
    {
        if (!$result) {
            throw new EntityNotFoundException('quiz not found');
        }

        $this->entityManager->remove($result);
        $this->entityManager->flush();

        $this->addFlash('success', 'Тест удален успешно');
        return $this->redirectToRoute('admin_result_index');

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

