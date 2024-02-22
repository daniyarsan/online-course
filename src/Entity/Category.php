<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"admin"})
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Quiz::class, mappedBy="category")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quizes;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"admin"})
     */
    private $active = true;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin", "user"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Course::class, mappedBy="category", orphanRemoval=true)
     */
    private $courses;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getQuizes()
    {
        return $this->quizes;
    }

    public function setQuizes($quizes): self
    {
        $this->quizes = $quizes;
        return $this;
    }

    public function addAnswer(Quiz $quiz): self
    {
        if (!$this->quizes->contains($quiz)) {
            $this->quizes[] = $quiz;
            $quiz->setCategory($this);
        }
        return $this;
    }

    public function removeQuiz(Quiz $quiz): self
    {
        if ($this->quizes->removeElement($quiz)) {
            if ($quiz->getCategory() === $this) {
                $quiz->setCategory(null);
            }
        }
        return $this;
    }

    public function __toString()
    {
        return $this->name; 
    }

    /**
     * @return Collection<int, Course>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses[] = $course;
            $course->setCategory($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): self
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getCategory() === $this) {
                $course->setCategory(null);
            }
        }

        return $this;
    }
}

