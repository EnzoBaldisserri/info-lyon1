<?php

namespace App\Entity\Control;

use DateTime;
use RunTimeException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Control\ControlRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="controlType", type="string", length=20)
 */
abstract class Control
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=45)
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $coefficient;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $divisor;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $median;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $average;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $standardDeviation;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Control\Mark", mappedBy="control", orphanRemoval=true)
     */
    private $marks;

    public function __construct()
    {
        $this->marks = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
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

    public function getCoefficient(): ?float
    {
        return $this->coefficient;
    }

    public function setCoefficient(float $coefficient): self
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    public function getDivisor(): ?int
    {
        return $this->divisor;
    }

    public function setDivisor(int $divisor): self
    {
        $this->divisor = $divisor;

        return $this;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getMedian(): ?float
    {
        return $this->median;
    }

    public function setMedian(float $median): self
    {
        if ($median > $this->divisor) {
            throw new RuntimeException('Median is greater than divisor');
        }

        $this->median = $median;

        return $this;
    }

    public function getAverage(): ?float
    {
        return $this->average;
    }

    public function setAverage(float $average): self
    {
        if ($average > $this->divisor) {
            throw new RuntimeException('Average is greater than divisor');
        }

        $this->average = $average;

        return $this;
    }

    public function getStandardDeviation(): ?float
    {
        return $this->standardDeviation;
    }

    public function setStandardDeviation(float $standardDeviation): self
    {
        if ($standardDeviation > $this->divisor) {
            throw new RuntimeException('Standard deviation is greater than divisor');
        }

        $this->standardDeviation = $standardDeviation;

        return $this;
    }

    /**
     * @return Collection|Mark[]
     */
    public function getMarks(): Collection
    {
        return $this->marks;
    }

    public function addMark(Mark $mark): self
    {
        if (!$this->marks->contains($mark)) {
            $this->marks[] = $mark;
            $mark->setControl($this);
        }

        return $this;
    }

    public function removeMark(Mark $mark): self
    {
        if ($this->marks->contains($mark)) {
            $this->marks->removeElement($mark);
            // set the owning side to null (unless already changed)
            if ($mark->getControl() === $this) {
                $mark->setControl(null);
            }
        }

        return $this;
    }
}
