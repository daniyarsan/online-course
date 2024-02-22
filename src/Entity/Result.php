<?php

namespace App\Entity;

use App\Repository\ResultsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=ResultsRepository::class)
 */
class Result
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="results")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"admin"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Quiz::class)
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @Groups({"admin", "user"})
     */
    private $quiz;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"admin", "user"})
     *
     */
    private $timeSpent;

    /**
     * @ORM\OneToMany(targetEntity=Answer::class, mappedBy="result", orphanRemoval=true)
     * @Groups({"admin"})
     */
    private $answers;


    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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


    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setResult($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getResult() === $this) {
                $answer->setResult(null);
            }
        }

        return $this;
    }

    public function getTimeSpent()
    {
        return $this->timeSpent;
    }

    public function getTimeSpentHuman()
    {
        return gmdate("H:i:s", $this->getTimeSpent());
    }


    public function getTimeLeft()
    {
        return $this->quiz->getTimeLimit() * 60 - $this->timeSpent;
    }

    public function getPercentageTimeLeft()
    {
        return  round(($this->getTimeLeft() * 100) / ($this->quiz->getTimeLimit() * 60));
    }

    public function setTimeSpent($timeSpent): void
    {
        $this->timeSpent = $timeSpent;
    }


    /**
     * @Groups({"admin", "user"})
     */
    public function getScore(): ?int
    {
        $scores = $this->answers
            ->map(function(Answer $answer) { return $answer->getScore(); })
            ->getValues();

        return array_sum($scores);
    }


}
