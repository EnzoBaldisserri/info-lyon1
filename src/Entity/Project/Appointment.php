<?php

namespace App\Entity\Project;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Project\AppointmentRepository")
 */
class Appointment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project\Project")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Project\DateProposal", mappedBy="appointment", orphanRemoval=true)
     */
    private $dateProposals;

    public function __construct()
    {
        $this->dateProposals = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection|DateProposal[]
     */
    public function getDateProposals(): Collection
    {
        return $this->dateProposals;
    }

    public function addDateProposal(DateProposal $dateProposal): self
    {
        if (!$this->dateProposals->contains($dateProposal)) {
            $this->dateProposals[] = $dateProposal;
            $dateProposal->setAppointment($this);
        }

        return $this;
    }

    public function removeDateProposal(DateProposal $dateProposal): self
    {
        if ($this->dateProposals->contains($dateProposal)) {
            $this->dateProposals->removeElement($dateProposal);
            // set the owning side to null (unless already changed)
            if ($dateProposal->getAppointment() === $this) {
                $dateProposal->setAppointment(null);
            }
        }

        return $this;
    }
}
