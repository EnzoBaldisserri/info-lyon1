<?php

namespace App\Entity\Administration;

use App\Entity\User\Student;
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
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $number;

    /**
     * @var Semester
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Administration\Semester", inversedBy="groups", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $semester;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\User\Student", mappedBy="classes", cascade={"persist"})
     */
    private $students;

    public function __construct()
    {
        $this->students = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return string
     */
    public function getName(): ?string
    {
        return sprintf('G%d', $this->number);
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return string
     */
    public function getFullname(): ?string
    {
        return sprintf('G%d%s', $this->number, $this->semester->getName());
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

    public function setSemester(?Semester $semester): self
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
