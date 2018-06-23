<?php

namespace App\Entity\Administration;

use App\Entity\Administration\Course;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Administration\TeachingUnitRepository")
 */
class TeachingUnit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="date")
     * @Assert\Date()
     */
    private $implementationDate;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Administration\Course", inversedBy="teachingUnits", cascade={"persist", "remove"})
     */
    private $courses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Administration\Module", mappedBy="teachingUnit", orphanRemoval=true)
     */
    private $modules;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
        $this->modules = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

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

    public function getFullName(): ?string
    {
        return sprintf('%s - %s', $this->code, $this->name);
    }

    public function getImplementationDate(): ?\DateTimeInterface
    {
        return $this->implementationDate;
    }

    public function setImplementationDate(\DateTimeInterface $implementationDate): self
    {
        $this->implementationDate = $implementationDate;

        return $this;
    }

    /**
     * @return Collection|Course[]
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): self
    {
        if (!$this->courses->contains($course)) {
            $this->courses[] = $course;
        }

        return $this;
    }

    public function removeCourse(Course $course): self
    {
        if ($this->courses->contains($course)) {
            $this->courses->removeElement($course);
        }

        return $this;
    }

    /**
     * @return Collection|Module[]
     */
    public function getModules(): Collection
    {
        return $this->modules;
    }

    public function addModule(Module $module): self
    {
        if (!$this->modules->contains($module)) {
            $this->modules[] = $module;
            $module->setTeachingUnit($this);
        }

        return $this;
    }

    public function removeModule(Module $module): self
    {
        if ($this->modules->contains($module)) {
            $this->modules->removeElement($module);
            // set the owning side to null (unless already changed)
            if ($module->getTeachingUnit() === $this) {
                $module->setTeachingUnit(null);
            }
        }

        return $this;
    }
}
