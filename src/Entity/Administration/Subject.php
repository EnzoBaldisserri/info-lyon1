<?php

namespace App\Entity\Administration;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Administration\SubjectRepository")
 */
class Subject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private $coefficient;

    /**
     * @var Module
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Administration\Module", inversedBy="subjects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $module;

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
        if ($this->name === null) {
            return $this->module->getName();
        }

        return $this->name;
    }

    public function getFullName(): ?string
    {
        $fullname = $this->module->getName();
        if ($this->name !== null) {
            $fullname .= ' - ' . $this->name;
        }

        return $fullname;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCoefficient(): ?float
    {
        return $this->coefficient;
    }

    public function setCoefficient(float $coefficient): self
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): self
    {
        $this->module = $module;

        return $this;
    }
}
