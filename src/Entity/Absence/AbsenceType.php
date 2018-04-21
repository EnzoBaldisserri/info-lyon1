<?php

namespace App\Entity\Absence;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Absence\AbsenceTypeRepository")
 */
class AbsenceType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=48)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $class;

    public function getId()
    {
        return $this->id;
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

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
    }

}
