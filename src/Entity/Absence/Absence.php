<?php

namespace App\Entity\Absence;

use DateTime;
use App\Entity\User\Student;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    private $startTime;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    private $endTime;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $justified;

    /**
     * @var AbsenceType
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Absence\AbsenceType")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @var Student
     *
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
