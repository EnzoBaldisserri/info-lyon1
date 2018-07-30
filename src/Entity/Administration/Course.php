<?php

namespace App\Entity\Administration;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Administration\CourseRepository")
 */
class Course
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
     * @ORM\Column(type="smallint")
     */
    private $semester;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date")
     * @Assert\Date()
     */
    private $implementationDate;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Semester", mappedBy="course", cascade={"persist"})
     */
    private $semesters;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="TeachingUnit", mappedBy="courses", cascade={"persist"})
     */
    private $teachingUnits;

    public function __construct()
    {
        $this->semesters = new ArrayCollection();
        $this->teachingUnits = new ArrayCollection();
    }

    public function isEditable()
    {
        $today = new DateTime();
        return $today < $this->implementationDate;
    }

    public function isDeletable()
    {
        return $this->isEditable() && $this->semesters->isEmpty();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): string
    {
        return sprintf('S%d', $this->semester);
    }

    public function getSemester(): ?int
    {
        return $this->semester;
    }

    public function setSemester(int $semester): self
    {
        $this->semester = $semester;

        return $this;
    }

    public function getImplementationDate(): ?DateTime
    {
        return $this->implementationDate;
    }

    public function setImplementationDate(DateTime $implementationDate): self
    {
        $this->implementationDate = $implementationDate;

        return $this;
    }

    /**
     * @return Collection|Semester[]
     */
    public function getSemesters(): Collection
    {
        return $this->semesters;
    }

    public function addSemester(Semester $semester): self
    {
        if (!$this->semesters->contains($semester)) {
            $this->semesters[] = $semester;
            $semester->setCourse($this);
        }

        return $this;
    }

    public function removeSemester(Semester $semester): self
    {
        if ($this->semesters->contains($semester)) {
            $this->semesters->removeElement($semester);
            // set the owning side to null (unless already changed)
            if ($semester->getCourse() === $this) {
                $semester->setCourse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TeachingUnit[]
     */
    public function getTeachingUnits(): Collection
    {
        return $this->teachingUnits;
    }

    public function addTeachingUnit(TeachingUnit $teachingUnit): self
    {
        if (!$this->teachingUnits->contains($teachingUnit)) {
            $this->teachingUnits[] = $teachingUnit;
            $teachingUnit->addCourse($this);
        }

        return $this;
    }

    public function removeTeachingUnit(TeachingUnit $teachingUnit): self
    {
        if ($this->teachingUnits->contains($teachingUnit)) {
            $this->teachingUnits->removeElement($teachingUnit);
            $teachingUnit->removeCourse($this);
        }

        return $this;
    }
}
