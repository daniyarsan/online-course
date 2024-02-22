<?php

namespace App\Entity;

use App\Repository\DepartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=DepartmentRepository::class)
 */
class Department
{
    public const MODERATOR = 'Модераторы';
    public const SECURITY = 'Служба Безопасности';
    public const DEVELOPER = 'Разработчики';
    public const TESTER = 'Тестировщики';
    public const DEVOPS = 'DevOps';
    public const PR = 'PR';

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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="department")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=Quiz::class, inversedBy="departments", cascade={"persist"})
     */
    private $quiz;


    public function __construct($id = false)
    {
        if ($id) {
            $this->id = $id;
        }
        $this->user = new ArrayCollection();
        $this->quiz = new ArrayCollection();
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

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setDepartment($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getDepartment() === $this) {
                $user->setDepartment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Quiz>
     */
    public function getQuiz(): Collection
    {
        return $this->quiz;
    }

    public function addQuiz(Quiz $quiz): self
    {
        if (!$this->quiz->contains($quiz)) {
            $this->quiz[] = $quiz;
        }

        return $this;
    }

    public function removeQuiz(Quiz $quiz): self
    {
        $this->quiz->removeElement($quiz);

        return $this;
    }

    public function __toString():string
    {
        return $this->name;
    }

}
