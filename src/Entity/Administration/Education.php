<?php

namespace App\Entity\Administration;

use App\Entity\User\Teacher;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Administration\EducationRepository")
 */
class Education
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Group
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Administration\Group")
     * @ORM\JoinColumn(nullable=false)
     */
    private $group;

    /**
     * @var Subject
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Administration\Subject")
     * @ORM\JoinColumn(nullable=false)
     */
    private $subject;

    /**
     * @var Teacher
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User\Teacher", inversedBy="educations")
     */
    private $teacher;

    public function getId()
    {
        return $this->id;
    }

    public function getGroup(): ?Group
    {
        return $this->group;
    }

    public function setGroup(?Group $group): self
    {
        $this->group = $group;

        return $this;
    }

    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    public function setSubject(?Subject $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }

}
