<?php

namespace App\Entity\Administration;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="smallint")
     */
    private $semester;

    /**
     * @ORM\Column(type="date")
     */
    private $implementationYear;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Administration\TeachingUnit")
     */
    private $teachingUnits;

    public function __construct()
    {
        $this->teachingUnits = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
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

    public function getImplementationYear(): ?\DateTimeInterface
    {
        return $this->implementationYear;
    }

    public function setImplementationYear(\DateTimeInterface $implementationYear): self
    {
        $this->implementationYear = $implementationYear;

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
        }

        return $this;
    }

    public function removeTeachingUnit(TeachingUnit $teachingUnit): self
    {
        if ($this->teachingUnits->contains($teachingUnit)) {
            $this->teachingUnits->removeElement($teachingUnit);
        }

        return $this;
    }

}
