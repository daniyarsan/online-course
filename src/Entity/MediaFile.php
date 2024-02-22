<?php

namespace App\Entity;

use App\Repository\MediaFileRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=MediaFileRepository::class)
 */
class MediaFile
{
    use TimestampableTrait;

    const UPLOADS_DIR = 'uploads';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin", "user"})
     *
     */
    private $path;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin", "user"})
     *
     */
    private $uid;

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param mixed $uid
     */
    public function setUid($uid): void
    {
        $this->uid = $uid;
    }

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin", "user"})
     *
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Lesson::class, inversedBy="mediaFile", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $lesson;



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

    public function getLesson(): ?Lesson
    {
        return $this->lesson;
    }

    public function setLesson(?Lesson $lesson): self
    {
        $this->lesson = $lesson;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path): void
    {
        $this->path = $path;
    }

    /**
     * @Groups({"admin", "user"})
     */
    public function getUploadsDirPath()
    {
        return '/'.self::UPLOADS_DIR.'/' . $this->path;
    }


}
