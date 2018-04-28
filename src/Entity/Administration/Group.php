<?php

namespace App\Entity\Administration;

use App\Entity\User\Student;
use App\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Administration\GroupRepository")
 * @ORM\Table(name="`group`")
 */
class Group
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
    private $number;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Administration\Semester", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $semester;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User\Student", mappedBy="classes")
     */
    private $students;

    /**
     * @Serializer\Accessor(getter="getName")
     */
    private $name;

    /**
     * @Serializer\Accessor(getter="getFullname")
     */
    private $fullname;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->students = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        if (!isset($this->name)) {
            $this->name = 'G' . $this->number;
        }

        return $this->name;
    }

    public function getFullname(): ?string
    {
        if (!isset($this->fullname)) {
            $this->fullname = $this->getName() . $this->semester->getName();
        }

        return $this->fullname;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getSemester(): ?Semester
    {
        return $this->semester;
    }

    public function setSemester(Semester $semester): self
    {
        $this->semester = $semester;

        return $this;
    }

    /**
     * @return Collection|Student[]
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): self
    {
        if (!$this->students->contains($student)) {
            $this->students[] = $student;
            $student->addClass($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        if ($this->students->contains($student)) {
            $this->students->removeElement($student);
            $student->removeClass($this);
        }

        return $this;
    }
}
