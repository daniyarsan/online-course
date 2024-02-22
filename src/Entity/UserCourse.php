<?php

namespace App\Entity;

use App\Repository\UserCourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserCourseRepository::class)
 */
class UserCourse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userCourses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Course::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"admin", "user"})
     *
     */
    private $course;

    /**
     * @ORM\OneToMany(targetEntity=LessonResult::class, mappedBy="userCourse", orphanRemoval=true, cascade={"persist", "remove"})
     * @Groups({"admin", "user"})
     *
     */
    private $lessonResults;

    /**
     * @ORM\ManyToOne(targetEntity=Lesson::class)
     * @ORM\JoinColumn(name="current_lesson_id", referencedColumnName="id", onDelete="CASCADE")
     * @Groups({"admin", "user"})
     *
     */
    private $currentLesson;


    public function __construct()
    {
        $this->lessonResults = new ArrayCollection();
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

    public function getCourse(): ?course
    {
        return $this->course;
    }

    public function setCourse(?course $course): self
    {
        $this->course = $course;

        return $this;
    }

    /**
     * @return Collection<int, LessonResult>
     */
    public function getLessonResults(): Collection
    {
        return $this->lessonResults;
    }

    public function addLessonResult(LessonResult $lessonResult): self
    {
        if (!$this->lessonResults->contains($lessonResult)) {
            $this->lessonResults[] = $lessonResult;
            $lessonResult->setUserCourse($this);
        }

        return $this;
    }

    public function removeLessonResult(LessonResult $lessonResult): self
    {
        if ($this->lessonResults->removeElement($lessonResult)) {
            // set the owning side to null (unless already changed)
            if ($lessonResult->getUserCourse() === $this) {
                $lessonResult->setUserCourse(null);
            }
        }

        return $this;
    }

    public function getCurrentLesson(): ?Lesson
    {
        return $this->currentLesson;
    }

    public function setCurrentLesson(?Lesson $currentLesson): self
    {
        $this->currentLesson = $currentLesson;

        return $this;
    }


}
