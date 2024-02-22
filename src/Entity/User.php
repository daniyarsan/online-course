<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity (repositoryClass=UserRepository::class)
 * @ORM\Table(name="user")
 * @UniqueEntity("username")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';

    use TimestampableTrait;

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
     *
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @var string
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"user"})
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity=Result::class, mappedBy="user", orphanRemoval=true)
     *
     */
    private $results;

    /**
     * @ORM\Column(type="json")
     * @Groups({"admin"})
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $messages;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class, inversedBy="user")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"admin", "user"})
     *
     */
    private $department;

    /**
     * @ORM\OneToMany(targetEntity=UserCourse::class, mappedBy="user", orphanRemoval=true)
     */
    private $userCourses;


    public function __construct()
    {
        $this->roles = [self::ROLE_USER];
        $this->results = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->userCourses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return Collection<int, Result>
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function addResult(Result $result): self
    {
        if (!$this->results->contains($result)) {
            $this->results[] = $result;
            $result->setUser($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->results->removeElement($result)) {
            // set the owning side to null (unless already changed)
            if ($result->getUser() === $this) {
                $result->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }

        return $this;
    }


    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(Department $department): self
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar): void
    {
        $this->avatar = $avatar;
    }


    public function getTotalScore():?int
    {
        $scores = $this->results->map(function (Result $result) {
            return $result->getScore();
        })->getValues();

        return array_sum($scores);
    }

    /**
     * @return Collection<int, UserCourse>
     */
    public function getUserCourses(): Collection
    {
        return $this->userCourses;
    }

    public function addUserCourse(UserCourse $userCourse): self
    {
        if (!$this->userCourses->contains($userCourse)) {
            $this->userCourses[] = $userCourse;
            $userCourse->setUser($this);
        }

        return $this;
    }

    public function removeUserCourse(UserCourse $userCourse): self
    {
        if ($this->userCourses->removeElement($userCourse)) {
            // set the owning side to null (unless already changed)
            if ($userCourse->getUser() === $this) {
                $userCourse->setUser(null);
            }
        }

        return $this;
    }

    public function hasCourse(Course $course)
    {
        $userCourseRecord = $this->userCourses->filter(function (UserCourse $userCourse) use ($course) {
            return $userCourse->getCourse()->getId() === $course->getId();
        });

        return $userCourseRecord->count() > 0;
    }
}
