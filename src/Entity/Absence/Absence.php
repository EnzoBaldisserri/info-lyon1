<?php

namespace App\Entity\Absence;

use App\Entity\Absence\AbsenceType;
use App\Entity\Administration\Semester;
use App\Entity\User\Student;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Absence\AbsenceRepository")
 */
class Absence
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
    private $startTime;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endTime;

    /**
     * @ORM\Column(type="boolean")
     */
    private $justified;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Absence\AbsenceType")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\Student", inversedBy="absences")
     * @ORM\JoinColumn(nullable=false)
     */
    private $student;

    public function __construct() {
        $this->justified = false;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function isJustified(): ?bool
    {
        return $this->justified;
    }

    public function setJustified(bool $justified): self
    {
        $this->justified = $justified;

        return $this;
    }

    public function getType(): ?AbsenceType
    {
        return $this->type;
    }

    public function setType(?AbsenceType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        return $this;
    }

}
