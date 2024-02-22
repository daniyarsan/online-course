<?php

namespace App\Form\SearchForms;

use App\Repository\CategoryRepository;
use App\Repository\DepartmentRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizSearchType extends AbstractType
{
    private DepartmentRepository $departmentRepository;
    private CategoryRepository $categoryRepository;

    public function __construct(DepartmentRepository $departmentRepository, CategoryRepository $categoryRepository)
    {
        $this->departmentRepository = $departmentRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setRequired(false)->setMethod('GET')
            ->add('department', ChoiceType::class, [
                    'choices' => $this->fillDepartments(),
                    'placeholder' => 'Любой отдел'
                ]
            )
            ->add('category', ChoiceType::class, [
                    'choices' => $this->fillCategories(),
                    'placeholder' => 'Любая категория'
                ]
            );

    }

    public function fillDepartments(): array
    {
        $list = [];
        $departmentList = $this->departmentRepository->findAll();
        foreach ($departmentList as $departmentItem) {
            $list[$departmentItem->getName()] = $departmentItem->getId();
        }
        return $list;
    }

    public function fillCategories(): array
    {
        $list = [];
        $categoriesList = $this->categoryRepository->findAll();
        foreach ($categoriesList as $categoriesItem) {
            $list[$categoriesItem->getName()] = $categoriesItem->getId();
        }
        return $list;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}
