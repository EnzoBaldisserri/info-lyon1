<?php

namespace App\Entity\Schedule;

use DateTime;
use App\Entity\Administration\Group;
use App\Entity\User\Teacher;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Schedule\LessonRepository")
 */
class Lesson
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $resources;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=45)
     */
    private $name;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $startTime;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $endTime;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Administration\Group")
     */
    private $groups;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\User\Teacher")
     */
    private $teachers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Schedule\Room")
     */
    private $rooms;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
        $this->teachers = new ArrayCollection();
        $this->rooms = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getResources(): ?int
    {
        return $this->resources;
    }

    public function setResources(int $resources): self
    {
        $this->resources = $resources;

        return $this;
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

    public function getStartTime(): ?DateTime
    {
        return $this->startTime;
    }

    public function setStartTime(DateTime $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?DateTime
    {
        return $this->endTime;
    }

    public function setEndTime(DateTime $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->contains($group)) {
            $this->groups->removeElement($group);
        }

        return $this;
    }

    /**
     * @return Collection|Teacher[]
     */
    public function getTeachers(): Collection
    {
        return $this->teachers;
    }

    public function addTeacher(Teacher $teacher): self
    {
        if (!$this->teachers->contains($teacher)) {
            $this->teachers[] = $teacher;
        }

        return $this;
    }

    public function removeTeacher(Teacher $teacher): self
    {
        if ($this->teachers->contains($teacher)) {
            $this->teachers->removeElement($teacher);
        }

        return $this;
    }

    /**
     * @return Collection|Room[]
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function addRoom(Room $room): self
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms[] = $room;
        }

        return $this;
    }

    public function removeRoom(Room $room): self
    {
        if ($this->rooms->contains($room)) {
            $this->rooms->removeElement($room);
        }

        return $this;
    }
}
