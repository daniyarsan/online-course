<?php

namespace App\Entity;

use App\Repository\AnswersRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AnswersRepository::class)
 */
class Answer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"admin"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Result::class, inversedBy="answers")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Result $result;


    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin"})
     */
    private $questionText;

    /**
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin", "user"})
     */
    private $type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"admin"})
     */
    private $score;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="answers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $question;


    /**
     * @return mixed
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @param mixed $score
     */
    public function setScore($score): void
    {
        $this->score = $score;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResult(): ?Result
    {
        return $this->result;
    }

    public function setResult(?Result $result): self
    {
        $this->result = $result;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content): void
    {
        $this->content = $content;
    }


    public function getQuestionText()
    {
        return $this->questionText;
    }


    public function setQuestionText($questionText): void
    {
        $this->questionText = $questionText;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function isCorrect()
    {
        return $this->score > 0;
    }

}

