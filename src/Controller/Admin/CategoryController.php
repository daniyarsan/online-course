<?php

namespace App\Controller\Admin;

use App\Entity\Answer;
use App\Entity\Category;
use App\Entity\Quiz;
use App\Form\CategoryType;
use App\Form\EvaluateType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/category", name="admin_category")
 */
class CategoryController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("", name="_index", methods={"GET"})
     */
    public function index(Request $request, CategoryRepository $repository)
    {
        $result = $repository->getListByFilter([]);

        $form = $this->createForm(EvaluateType::class, new Answer());
        return $this->render('admin/category/index.html.twig', [
            'data' => $result,
            'evaluateForm' => $form->createView()
        ]);


    }

    /**
     * @Route("/create", name="_create")
     */
    public function create(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($category);
            $this->entityManager->flush();

            return $this->redirect($this->generateUrl('admin_category_edit', ['id' => $category->getId()]));
        }

        return $this->render('admin/category/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="_edit")
     * @ParamConverter ("category", class="App\Entity\Category")
     */
    public function edit($category, Request $request): Response
    {
        if (!$category) {
            throw new EntityNotFoundException('Нет категории с данным идентификатором');
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($category);
            $this->entityManager->flush();
//            $this->service->saveData($form, $doctrine);

            return $this->redirect($this->generateUrl('admin_category_edit', ['id' => $category->getId()]));
        }


        return $this->render('admin/category/edit.html.twig', [
            'form' => $form->createView(),
            'category' => $category
        ]);
    }

    /**
     * @Route("/delete/{id}", name="_delete", methods={"POST"})
     * @ParamConverter ("category", class="App\Entity\Category")
     */
    public function delete($category, EntityManagerInterface $manager): RedirectResponse
    {
        if (!$category) {
            throw new EntityNotFoundException('category not found');
        }

        $this->entityManager->remove($category);
        $this->entityManager->flush();

        $this->addFlash('success', 'Тест удален успешно');
        return $this->redirectToRoute('admin_category_index');

    }

    /**
     * @Route("/category/{category}/quiz/{quiz}/remove", name="_quiz_remove", methods={"POST"})
     * @ParamConverter ("category", class="App\Entity\Category")
     * @ParamConverter ("quiz", class="App\Entity\Quiz")
     */
    public function removeQuiz(Category $category, Quiz $quiz): RedirectResponse
    {
        if(!$category) {
            throw new EntityNotFoundException('category not found');
        }
        if(!$quiz) {
            throw new EntityNotFoundException('quiz not found');
        }

        $category->removeQuiz($quiz);
        $this->entityManager->flush();

        $this->addFlash('success', 'Тест из категории удален успешно');
        return $this->redirectToRoute('admin_category_edit', ['id' => $category->getId()]);
    }
}