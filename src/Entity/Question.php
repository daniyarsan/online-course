<?php

namespace App\Entity;

use App\Exception\LogicException;
use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 */
class Question
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"admin", "user"})
     *
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin", "user"})
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity=Quiz::class, inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quiz;

    /**
     * @ORM\OneToMany(targetEntity=Choice::class, mappedBy="question", orphanRemoval=true, cascade={"persist"})
     * @Groups({"admin", "user"})
     *
     */
    private $choices;

    /**
     * @ORM\OneToMany(targetEntity=Answer::class, mappedBy="question", orphanRemoval=true)
     */
    private $answers;

    public function __construct()
    {
        $this->choices = new ArrayCollection();
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Choice>
     */
    public function getChoices(): Collection
    {
        return $this->choices;
    }

    public function getChoice(int $id)
    {
        /** @var Choice $choice */
        foreach ($this->choices as $choice) {
            if ($choice->getId() == $id) {
                return $choice;
            }
        }
        return false;
    }

    public function getChoiceByDescription(string $description)
    {
        /** @var Choice $choice */
        foreach ($this->choices as $choice) {
            if ($choice->getDescription() == $description) {
                return $choice;
            }
        }
        return false;
    }

    public function addChoice(Choice $choice): self
    {
        if (!$this->choices->contains($choice)) {
            $this->choices[] = $choice;
            $choice->setQuestion($this);
        }

        return $this;
    }


    public function removeChoice(Choice $choice): self
    {
        if ($this->choices->removeElement($choice)) {
            // set the owning side to null (unless already changed)
            if ($choice->getQuestion() === $this) {
                $choice->setQuestion(null);
            }
        }

        return $this;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): self
    {
        $this->quiz = $quiz;

        return $this;
    }

    public function hasChoice(string $desription)
    {
        /** @var Choice $choice */
        foreach ($this->choices as $choice) {
            if ($choice->getDescription() == $desription) {
                return true;
            }
        }
        return false;
    }

    /**
     * @Assert\Callback
    */
    public function validate(ExecutionContextInterface $context, $payload)
    {
//        if (!$this->getCorrect()) {
//            $context->buildViolation('Минимум один вопрос должен быть правильный')->atPath('choices')->addViolation();
//        }
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

        return $this;
    }

    public function getCorrectChoices(): array
    {
//        dump($this->getId());
        return $this->choices->filter(function(Choice $choice) { return $choice->getScore() > 0; })->getValues();
    }



}
