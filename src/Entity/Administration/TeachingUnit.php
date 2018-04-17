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
    private $implementationYear;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

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

    public function getImplementationYear(): ?\DateTimeInterface
    {
        return $this->implementationYear;
    }

    public function setImplementationYear(\DateTimeInterface $implementationYear): self
    {
        $this->implementationYear = $implementationYear;

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