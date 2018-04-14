<?php

namespace App\Entity\Administration;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Administration\SemesterRepository")
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
     * @ORM\Column(type="integer")
     */
    private $schoolYear;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deferred;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Administration\Course", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $course;

    public function __construct() {
        parent::__construct();

        $this->active = false;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return 'S' . $this->course->getSemester();
    }

    public function getSchoolYear(): ?int
    {
        return $this->schoolYear;
    }

    public function setSchoolYear(int $schoolYear): self
    {
        $this->schoolYear = $schoolYear;

        return $this;
    }

    public function getDeferred(): ?bool
    {
        return $this->deferred;
    }

    public function setDeferred(bool $deferred): self
    {
        $this->deferred = $deferred;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

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
}
