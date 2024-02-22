<?php

namespace App\Form\SearchForms;

use App\Repository\DepartmentRepository;
use App\Repository\QuizRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserSearchType extends AbstractType
{
    private DepartmentRepository $departmentRepository;
    private QuizRepository $quizRepository;

    public function __construct(
        DepartmentRepository $departmentRepository, QuizRepository $quizRepository)
    {
        $this->departmentRepository = $departmentRepository;
        $this->quizRepository = $quizRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setRequired(false)->setMethod('GET')
            ->add('department', ChoiceType::class, [
                    'choices' => $this->fillDepartments(),
                    'placeholder' => 'Любой отдел',
                ]
            )
            ->add('quiz', ChoiceType::class, [
                    'choices' => $this->fillQuizes(),
                    'placeholder' => 'Любой Квиз'
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

    public function fillQuizes(): array
    {
        $list = [];
        $quizList = $this->quizRepository->findAll();
        foreach ($quizList as $quizItem) {
            $list[$quizItem->getTitle()] = $quizItem->getId();
        }
        return $list;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}
