<?php

namespace App\Entity\Administration;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="date")
     */
    private $implementationDate;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Administration\Course")
     * @ORM\JoinColumn(nullable=false)
     */
    private $course;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Administration\Module", mappedBy="teachingUnit", orphanRemoval=true)
     */
    private $modules;

    public function __construct()
    {
        $this->modules = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
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

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

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
