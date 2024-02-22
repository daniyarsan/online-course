<?php

namespace App\Entity;

use App\Repository\ChapterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ChapterRepository::class)
 */
class Chapter
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
     * @ORM\ManyToOne(targetEntity=Course::class, inversedBy="chapters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $course;

    /**
     * @ORM\OneToMany(targetEntity=Lesson::class, mappedBy="chapter", orphanRemoval=true)
     * @Groups({"admin", "user"})
     *
     */
    private $lessons;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"admin", "user"})
     *
     */
    private $sorting;


    public function __construct()
    {
        $this->lessons = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        return $this;
    }

    public function __toString():string
    {
        return $this->title;
    }

    /**
     * @return Collection<int, Lesson>
     */
    public function getLessons(): Collection
    {
        return $this->lessons;
    }

    public function addLesson(Lesson $lesson): self
    {
        if (!$this->lessons->contains($lesson)) {
            $this->lessons[] = $lesson;
            $lesson->setChapter($this);
        }

        return $this;
    }

    public function removeLesson(Lesson $lesson): self
    {
        if ($this->lessons->removeElement($lesson)) {
            // set the owning side to null (unless already changed)
            if ($lesson->getChapter() === $this) {
                $lesson->setChapter(null);
            }
        }

        return $this;
    }

    public function getSorting(): ?int
    {
        return $this->sorting;
    }

    public function setSorting(int $sorting): self
    {
        $this->sorting = $sorting;

        return $this;
    }
}
