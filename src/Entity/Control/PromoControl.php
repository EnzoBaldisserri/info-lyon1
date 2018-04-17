<?php

namespace App\Entity\Control;

use App\Entity\Administration\Subject;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Control\PromoControlRepository")
 */
class PromoControl extends Control
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Administration\Subject")
     */
    private $subject;

    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    public function setSubject(Subject $subject): self
    {
        $this->subject = $subject;

        return $this;
    }
}
