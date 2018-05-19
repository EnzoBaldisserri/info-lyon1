<?php

namespace App\Entity\Administration;

use App\Entity\Absence\Absence;
use App\Entity\Period;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Administration\SemesterRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class Semester
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Administration\Course", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $course;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Administration\Group", mappedBy="semester", orphanRemoval=true)
     * Excluded because causes serialization to break in Api\AbsenceController.getAll. Take a closer look ?
     * @Serializer\Exclude
     */
    private $groups;

    public function __construct() {
        $this->groups = new ArrayCollection();
    }

    public function isActive(\DateTimeInterface $datetime = null): ?bool
    {
        if ($datetime === null) {
            $datetime = new \DateTime();
        }

        // Check if datetime is between begin and end
        return $this->startDate < $datetime && $datetime < $this->endDate;
    }

    public function isEditable(): bool
    {
        // if semester isn't finished
        $today = new \DateTime();
        return $today < $this->endDate;
    }

    public function isDeletable(): bool
    {
        // if semester hasn't started
        $today = (new \DateTime());
        return $today < $this->startDate;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return 'S' . $this->course->getSemester();
    }

    public function getNumber(): ?int
    {
        return $this->course->getSemester();
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        // Start date's time must be the beginning of the day
        $startDate->setTime(0, 0, 0, 0);

        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        // End date's time must be the end of the day
        $endDate->setTime(23, 59, 59, 999);

        $this->endDate = $endDate;

        return $this;
    }

    public function getPeriod(): Period
    {
        return new Period($this->startDate, $this->endDate);
    }

    public function setPeriod(Period $period): self
    {
        $this->setStartDate($period->getStart());
        $this->setEndDate($period->getEnd());

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(Course $course): self
    {
        $this->course = $course;

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
            $group->setSemester($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->contains($group)) {
            $this->groups->removeElement($group);
            // set the owning side to null (unless already changed)
            if ($group->getSemester() === $this) {
                $group->setSemester(null);
            }
        }

        return $this;
    }
}
