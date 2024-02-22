<?php

namespace App\Business;

use App\Business\DTO\AnswerDto;
use App\Business\DTO\ResultDto;
use App\Entity\Quiz;
use App\Entity\Result;
use App\Entity\User;
use App\Exception\EntityAlreadyExistsException;
use App\Repository\ResultsRepository;
use Doctrine\ORM\EntityManagerInterface;

class ResultCreator
{
    private EntityManagerInterface $em;
    private ResultsRepository $resultRepository;
    private AnswerCreator $answerCreator;

    public function __construct(EntityManagerInterface $em, ResultsRepository $resultRepository, AnswerCreator $answerCreator)
    {
        $this->em = $em;
        $this->resultRepository = $resultRepository;
        $this->answerCreator = $answerCreator;
    }

    public function create(Quiz $quiz, ResultDto $resultDto, User $currentUser): Result
    {
        $hasResult = $this->resultRepository->findOneBy(['user' => $currentUser, 'quiz' => $quiz]);
        if ($hasResult) {
            throw new EntityAlreadyExistsException('User already passed this test');
        }

        $result = new Result();
        $result->setUser($currentUser);
        $result->setQuiz($quiz);
        $result->setTimeSpent($resultDto->getTime());

        foreach ($quiz->getQuestions() as $question) {
            $answerDto = $resultDto->findAnswerById($question->getId());
            if ($answerDto) {
                $answer = $this->answerCreator->create($question, $answerDto);
                $result->addAnswer($answer);
                $answer->setResult($result);
                $answer->setQuestion($question);
                $this->em->persist($answer);
            }
        }

        $this->em->persist($result);
        $this->em->flush();

        return $result;
    }
}