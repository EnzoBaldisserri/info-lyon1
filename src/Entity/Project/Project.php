<?php

namespace App\Entity\Project;

use App\Entity\User\Student;
use App\Entity\User\Teacher;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Project\ProjectRepository")
 */
class Project
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Teacher
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User\Teacher", inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tutor;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\User\Student")
     */
    private $members;

    public function __construct()
    {
        $this->members = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTutor(): ?Teacher
    {
        return $this->tutor;
    }

    public function setTutor(?Teacher $tutor): self
    {
        $this->tutor = $tutor;

        return $this;
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

    /**
     * @return Collection|Student[]
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(Student $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
        }

        return $this;
    }

    public function removeMember(Student $member): self
    {
        if ($this->members->contains($member)) {
            $this->members->removeElement($member);
        }

        return $this;
    }
}
