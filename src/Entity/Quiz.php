<?php

namespace App\Entity;

use App\Repository\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * @ORM\Entity(repositoryClass=QuizRepository::class)
 */
class Quiz
{
    use TimestampableTrait;

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
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"admin", "user"})
     *
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="quiz", orphanRemoval=true, cascade={"persist"})
     *
     */
    private $questions;


    /**
     * @ORM\ManyToMany(targetEntity=Department::class, mappedBy="quiz", cascade={"persist"})
     * @Groups({"admin", "user"})
     */
    private $departments;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"admin", "user"})
     */
    private $timeLimit;
    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="answers")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private ?Category $category;


    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->departments = new ArrayCollection();
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
     * @Groups({"admin", "user"})
     *
     */
    public function getQuestions()
    {
        return $this->questions->toArray();
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setQuiz($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getQuiz() === $this) {
                $question->setQuiz(null);
            }
        }

        return $this;
    }

    public function addTextQuestion(Question $question): self
    {
        if (!$this->textQuestions->contains($textQuestion)) {
            $this->textQuestions[] = $textQuestion;
            $textQuestion->setQuiz($this);
        }

        return $this;
    }

    public function removeTextQuestion(Question $textQuestion): self
    {
        if ($this->textQuestions->removeElement($textQuestion)) {
            // set the owning side to null (unless already changed)
            if ($textQuestion->getQuiz() === $this) {
                $textQuestion->setQuiz(null);
            }
        }

        return $this;
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Department>
     */
    public function getDepartments(): Collection
    {
        return $this->departments;
    }

    public function addDepartment(Department $department): self
    {
        if (!$this->departments->contains($department)) {
            $this->departments[] = $department;
            $department->addQuiz($this);
        }

        return $this;
    }

    public function removeDepartment(Department $department): self
    {
        if ($this->departments->removeElement($department)) {
            $department->removeQuiz($this);
        }

        return $this;
    }


    public function getDepartmentIds()
    {
        return $this->departments->map(function ($obj) {
            return $obj->getId();
        })->getValues();
    }

    public function getTimeLimit(): ?string
    {
        return $this->timeLimit;
    }

    public function setTimeLimit(string $timeLimit): self
    {
        $this->timeLimit = $timeLimit;

        return $this;
    }

    public function __toString(): string
    {
        return $this->title;
    }

    /**
     * @Groups({"admin", "user"})
     */
    public function getMaxPossibleScore()
    {
        $scores = $this->questions->map(function (Question $question) {
            $maxScore = 0;
            foreach ($question->getChoices() as $choice) {
                $maxScore = max($choice->getScore(), $maxScore);
            }

            return $maxScore;
        })->getValues();

        return array_sum($scores);
    }


    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;
        return $this;
    }
}
