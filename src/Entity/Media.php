<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=MediaRepository::class)
 */
class Media
{
    use TimestampableTrait;

    const SOURCE_YOUTUBE = 'youtube';
    const SOURCE_VIMEO = 'vimeo';
    const SOURCE_OTHER = 'other';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Lesson::class, inversedBy="media", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $lesson;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin", "user"})
     *
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin", "user"})
     *
     */
    private $url;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url): void
    {
        $this->url = $url;
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


    public function __toString()
    {
        return (string)$this->id;
    }

}
