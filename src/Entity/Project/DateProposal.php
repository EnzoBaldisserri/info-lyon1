<?php

namespace App\Entity\Project;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Project\DateProposalRepository")
 */
class DateProposal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Appointment
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Project\Appointment", inversedBy="dateProposals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $appointment;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Project\DateAccept", mappedBy="dateProposal", orphanRemoval=true)
     */
    private $dateAccepts;

    public function __construct()
    {
        $this->dateAccepts = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAppointment(): ?Appointment
    {
        return $this->appointment;
    }

    public function setAppointment(?Appointment $appointment): self
    {
        $this->appointment = $appointment;

        return $this;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection|DateAccept[]
     */
    public function getDateAccepts(): Collection
    {
        return $this->dateAccepts;
    }

    public function addDateAccept(DateAccept $dateAccept): self
    {
        if (!$this->dateAccepts->contains($dateAccept)) {
            $this->dateAccepts[] = $dateAccept;
            $dateAccept->setDateProposal($this);
        }

        return $this;
    }

    public function removeDateAccept(DateAccept $dateAccept): self
    {
        if ($this->dateAccepts->contains($dateAccept)) {
            $this->dateAccepts->removeElement($dateAccept);
            // set the owning side to null (unless already changed)
            if ($dateAccept->getDateProposal() === $this) {
                $dateAccept->setDateProposal(null);
            }
        }

        return $this;
    }
}
