<?php

namespace App\Entity\Control;

use App\Entity\Administration\Education;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Control\StandardControlRepository")
 */
class StandardControl extends Control
{
    /**
     * @var Education
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Administration\Education")
     */
    private $education;

    public function getEducation(): ?Education
    {
        return $this->education;
    }

    public function setEducation(Education $education): self
    {
        $this->education = $education;

        return $this;
    }
}
