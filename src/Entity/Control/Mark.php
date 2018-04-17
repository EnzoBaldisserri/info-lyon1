<?php

namespace App\Entity\Control;

use App\Entity\User\Student;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Control\MarkRepository")
 */
class Mark
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Control\Control", inversedBy="marks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $control;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\Student")
     * @ORM\JoinColumn(nullable=false)
     */
    private $student;

    public function getId()
    {
        return $this->id;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $divisor = $this->control->getDivisor();
        if ($value > $divisor) {
            throw new RuntimeException('La note est plus élevée que le diviseur du contrôle');
        }

        $this->value = $value;

        return $this;
    }

    public function getControl(): ?Control
    {
        return $this->control;
    }

    public function setControl(?Control $control): self
    {
        $this->control = $control;

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
