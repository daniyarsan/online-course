<?php

namespace App\Entity;

use App\Repository\LessonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=LessonRepository::class)
 */
class Lesson
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"admin", "user"})
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
    private $content;

    /**
     * @ORM\OneToMany(targetEntity=Media::class, mappedBy="lesson", orphanRemoval=true, cascade={"persist"})
     * @Groups({"admin", "user"})
     *
     */
    private $media;

    /**
     * @ORM\OneToMany(targetEntity=MediaFile::class, mappedBy="lesson", orphanRemoval=true, cascade={"persist"})
     * @Groups({"admin", "user"})
     *
     */
    private $mediaFile;

    /**
     * @ORM\ManyToOne(targetEntity=Course::class, inversedBy="lessons")
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $course;

    /**
     * @ORM\ManyToOne(targetEntity=Chapter::class, inversedBy="lessons", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $chapter;

    /**
     * @ORM\OneToMany(targetEntity=LessonResult::class, mappedBy="lesson", orphanRemoval=true)
     */
    private $lessonResults;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"admin", "user"})
     *
     */
    private $sorting;


    public function __construct()
    {
        $this->mediaFile = new ArrayCollection();
        $this->media = new ArrayCollection();
        $this->lessonResults = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
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
     * @return Collection<int, MediaFile>
     */
    public function getMediaFile(): Collection
    {
        return $this->mediaFile;
    }

    public function addMediaFile(MediaFile $mediaFile): self
    {
        if (!$this->mediaFile->contains($mediaFile)) {
            $this->mediaFile[] = $mediaFile;
            $mediaFile->setLesson($this);
        }

        return $this;
    }

    public function removeMediaFile(MediaFile $mediaFile): self
    {
        if ($this->mediaFile->removeElement($mediaFile)) {
            // set the owning side to null (unless already changed)
            if ($mediaFile->getLesson() === $this) {
                $mediaFile->setLesson(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): self
    {
        if (!$this->media->contains($medium)) {
            $this->media[] = $medium;
            $medium->setLesson($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): self
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getLesson() === $this) {
                $medium->setLesson(null);
            }
        }

        return $this;
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

    public function getChapter(): ?Chapter
    {
        return $this->chapter;
    }

    public function setChapter(?Chapter $chapter): self
    {
        $this->chapter = $chapter;

        return $this;
    }

     // Custom parameters

    /**
     * @Groups({"admin", "user"})
     *
     */
    public function getCourseId()
    {
        return $this->course->getId();
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
            $lessonResult->setLesson($this);
        }

        return $this;
    }

    public function removeLessonResult(LessonResult $lessonResult): self
    {
        if ($this->lessonResults->removeElement($lessonResult)) {
            // set the owning side to null (unless already changed)
            if ($lessonResult->getLesson() === $this) {
                $lessonResult->setLesson(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"admin", "user"})
     *
    */
    public function getChapterId()
    {
        return $this->chapter->getId();
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
