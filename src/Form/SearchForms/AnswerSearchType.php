<?php

namespace App\Form\SearchForms;

use App\Repository\QuizRepository;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswerSearchType extends AbstractType
{
    private QuizRepository $quizRepository;
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository, QuizRepository $quizRepository)
    {
        $this->userRepository = $userRepository;
        $this->quizRepository = $quizRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setRequired(false)->setMethod('GET')
            ->add('user', ChoiceType::class, [
                    'choices' => $this->fillUsers(),
                    'placeholder' => 'Любой сотрудник',
                ]
            )
            ->add('quiz', ChoiceType::class, [
                    'choices' => $this->fillQuizes(),
                    'placeholder' => 'Любой Квиз'
                ]
            );

    }

    public function fillUsers(): array
    {
        $list = [];
        $userList = $this->userRepository->findAll();
        foreach ($userList as $userItem) {
            $list[$userItem->getUsername()] = $userItem->getId();
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
