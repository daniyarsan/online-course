<?php

namespace App\Business;

use App\Business\DTO\AnswerDto;
use App\Entity\Answer;
use App\Entity\Choice;
use App\Entity\Question;
use App\Entity\QuestionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class AnswerCreator
{
    private EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
    }

    public function create(Question $question, AnswerDto $answerDto): Answer
    {
        $answer = new Answer();
        $answer->setQuestionText($question->getTitle());
        $answer->setType($answerDto->getType());

        if ($answerDto->getType() == Choice::TYPE_OPTION) {
            $choice = $this->em->getRepository(Choice::class)->findOneBy(['id' => $answerDto->getPayload()]);
            if (!$choice) {
                throw new EntityNotFoundException('No Choice found with passed id');
            }
            $answer->setContent($choice->getDescription());
            $currentChoice = $question->getChoice($answerDto->getPayload());
            $answer->setScore($currentChoice->getScore());
        }

        if ($answerDto->getType() == Choice::TYPE_TEXT) {
            $answer->setContent($answerDto->getPayload());
        }

        return $answer;
    }
}