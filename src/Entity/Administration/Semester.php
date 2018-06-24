<?php

namespace App\Entity\Administration;

use DateTime;
use App\Entity\Period;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     * @Assert\Date()
     */
    private $startDate;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     * @Assert\Date()
     */
    private $endDate;

    /**
     * @var Course
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Administration\Course", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $course;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Administration\Group",
     *     mappedBy="semester",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     * )
     * Excluded because causes serialization to break in Api\AbsenceController.getAll
     * @Serializer\Exclude
     */
    private $groups;

    public function __construct() {
        $this->groups = new ArrayCollection();
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload): void
    {
        $groupNumbers = array_map(
            function($group) { return $group->getNumber(); },
            $this->groups->toArray()
        );

        // Check for duplicates
        if (count($groupNumbers) !== count(array_flip($groupNumbers))) {
            $context->buildViolation('semester.groups.duplicate_numbers')
                ->atPath('groups')
                ->addViolation();
        };
    }

    public function isActive(DateTime $datetime = null): ?bool
    {
        if ($datetime === null) {
            $datetime = new DateTime();
        }

        // Check if datetime is between begin and end
        return $this->startDate < $datetime && $datetime < $this->endDate;
    }

    public function isEditable(): bool
    {
        // if semester isn't finished
        $today = new DateTime();
        return $today < $this->endDate;
    }

    public function isDeletable(): bool
    {
        // if semester hasn't started
        $today = (new DateTime());
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

    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(DateTime $startDate): self
    {
        // Start date's time must be the beginning of the day
        $startDate->setTime(0, 0, 0, 0);

        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(DateTime $endDate): self
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
