<?php

namespace App\Entity;

use App\Repository\LessonResultRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=LessonResultRepository::class)
 */
class LessonResult
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Lesson::class, inversedBy="lessonResults", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"admin", "user"})
     *
     */
    private $lesson;

    /**
     * @ORM\ManyToOne(targetEntity=UserCourse::class, inversedBy="lessonResults", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $userCourse;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLesson(): ?Lesson
    {
        return $this->lesson;
    }

    public function setLesson(?Lesson $lesson): self
    {
        $this->lesson = $lesson;

        return $this;
    }

    public function getUserCourse(): ?UserCourse
    {
        return $this->userCourse;
    }

    public function setUserCourse(?UserCourse $userCourse): self
    {
        $this->userCourse = $userCourse;

        return $this;
    }
}
