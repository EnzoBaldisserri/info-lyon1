<?php

namespace App\Entity\User;

use App\Entity\Absence\Absence;
use App\Entity\Administration\Group;
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Administration\Group", inversedBy="students")
     */
    private $classes;

    public function __construct() {
        parent::__construct();

        if (!$this->hasRole('ROLE_STUDENT')) {
            $this->addRole('ROLE_STUDENT');
        }

        $this->classes = new ArrayCollection();
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
}
