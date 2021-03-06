<?php

namespace App\Entity\User;

use App\Entity\Administration\Education;
use App\Entity\Administration\Referent;
use App\Entity\Project\Project;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\User\TeacherRepository")
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Teacher extends User
{
    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Administration\Referent", mappedBy="teacher", orphanRemoval=true)
     */
    private $referents;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Administration\Education", mappedBy="teacher", orphanRemoval=true)
     */
    private $educations;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Project\Project", mappedBy="tutor")
     */
    private $projects;

    public function __construct() {
        parent::__construct();

        $this->referents = new ArrayCollection();
        $this->educations = new ArrayCollection();
        $this->projects = new ArrayCollection();
    }

    /**
     * @return Collection|Referent[]
     */
    public function getReferents(): Collection
    {
        return $this->referents;
    }

    public function addReferent(Referent $referent): self
    {
        if (!$this->referents->contains($referent)) {
            $this->referents[] = $referent;
            $referent->setTeacher($this);
        }

        return $this;
    }

    public function removeReferent(Referent $referent): self
    {
        if ($this->referents->contains($referent)) {
            $this->referents->removeElement($referent);
            // set the owning side to null (unless already changed)
            if ($referent->getTeacher() === $this) {
                $referent->setTeacher(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Education[]
     */
    public function getEducations(): Collection
    {
        return $this->educations;
    }

    public function addEducation(Education $education): self
    {
        if (!$this->educations->contains($education)) {
            $this->educations[] = $education;
            $education->setTeacher($this);
        }

        return $this;
    }

    public function removeEducation(Education $education): self
    {
        if ($this->educations->contains($education)) {
            $this->educations->removeElement($education);
            // set the owning side to null (unless already changed)
            if ($education->getTeacher() === $this) {
                $education->setTeacher(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->setTutor($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->contains($project)) {
            $this->projects->removeElement($project);
            // set the owning side to null (unless already changed)
            if ($project->getTutor() === $this) {
                $project->setTutor(null);
            }
        }

        return $this;
    }
}
