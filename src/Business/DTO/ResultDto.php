<?php

namespace App\Business\DTO;


use App\Entity\User;
use App\Exception\ParamException;

class ResultDto
{
    private array $answers = [];
    private string $time;

    public function __construct(array $answers, $time)
    {
        $this->setAnswers($answers);
        $this->time = $time;
    }

    public function findAnswerById(int $id): ?AnswerDto
    {
        /* @var $answerDto AnswerDto */
        foreach ($this->answers as $answerDto) {
            if ($answerDto->getId() == $id) {
                return $answerDto;
            }
        }

        return null;
    }

    public function getAnswers(): array
    {
        return $this->answers;
    }

    public function getTime(): string
    {
        return $this->time;
    }

    private function setAnswers(array $answers) {
        foreach ($answers as $answer) {
            $this->answers[] = new AnswerDto($answer['id'], $answer['type'], $answer['payload']);
        }
    }

}