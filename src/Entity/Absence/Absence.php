<?php

namespace App\Entity\Absence;

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
    private $beginDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $justified;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\AbsenceType", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $absenceType;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $student;

    public function getId()
    {
        return $this->id;
    }

    public function getBeginDate(): ?\DateTimeInterface
    {
        return $this->beginDate;
    }

    public function setBeginDate(\DateTimeInterface $beginDate): self
    {
        $this->beginDate = $beginDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getJustified(): ?bool
    {
        return $this->justified;
    }

    public function setJustified(bool $justified): self
    {
        $this->justified = $justified;

        return $this;
    }

    public function getAbsenceType(): ?AbsenceType
    {
        return $this->absenceType;
    }

    public function setAbsenceType(AbsenceType $absenceType): self
    {
        $this->absenceType = $absenceType;

        return $this;
    }

    public function getStudent(): ?User
    {
        return $this->student;
    }

    public function setStudent(User $student): self
    {
        $this->student = $student;

        return $this;
    }
}
