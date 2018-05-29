<?php

namespace App\Entity\User;

use App\Entity\Absence\Absence;
use App\Entity\Administration\Group;
use App\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\User\StudentRepository")
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Student extends User
{
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Administration\Group", inversedBy="students")
     */
    private $classes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Absence\Absence", mappedBy="student", orphanRemoval=true)
     *
     * @Serializer\Expose
     */
    private $absences;

    public function __construct() {
        parent::__construct();

        $this->classes = new ArrayCollection();
        $this->absences = new ArrayCollection();
    }

    public function getFullName(): ?string
    {
        return sprintf('%s %s', $this->surname, $this->firstname);
    }

    /**
     * @return Collection|Group[]
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }

    public function addClass(Group $class): self
    {
        if (!$this->classes->contains($class)) {
            $this->classes[] = $class;
        }

        return $this;
    }

    public function removeClass(Group $class): self
    {
        if ($this->classes->contains($class)) {
            $this->classes->removeElement($class);
        }

        return $this;
    }

    /**
     * @return Collection|Absence[]
     */
    public function getAbsences(): Collection
    {
        return $this->absences;
    }

    public function addAbsence(Absence $absence): self
    {
        if (!$this->absences->contains($absence)) {
            $this->absences[] = $absence;
            $absence->setStudent($this);
        }

        return $this;
    }

    public function removeAbsence(Absence $absence): self
    {
        if ($this->absences->contains($absence)) {
            $this->absences->removeElement($absence);
            // set the owning side to null (unless already changed)
            if ($absence->getStudent() === $this) {
                $absence->setStudent(null);
            }
        }

        return $this;
    }

    public function setAbsences(Collection $absences): self
    {
        $this->absences = $absences;

        return $this;
    }
}
