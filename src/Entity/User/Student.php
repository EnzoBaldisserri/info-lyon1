<?php

namespace App\Entity\User;

use App\Entity\Absence\Absence;
use App\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\User\StudentRepository")
 */
class Student extends User
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Absence\Absence", mappedBy="student", orphanRemoval=true)
     */
    private $absences;

    public function __construct() {
        parent::__construct();

        if (!$this->hasRole('ROLE_STUDENT')) {
            $this->addRole('ROLE_STUDENT');
        }

        $this->absences = new ArrayCollection();
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
}
