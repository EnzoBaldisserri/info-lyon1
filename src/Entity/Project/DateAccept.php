<?php

namespace App\Entity\Project;

use App\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Project\DateAcceptRepository")
 */
class DateAccept
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var DateProposal
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Project\DateProposal", inversedBy="dateAccepts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $dateProposal;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $accepted;

    public function getId()
    {
        return $this->id;
    }

    public function getDateProposal(): ?DateProposal
    {
        return $this->dateProposal;
    }

    public function setDateProposal(?DateProposal $dateProposal): self
    {
        $this->dateProposal = $dateProposal;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAccepted(): ?bool
    {
        return $this->accepted;
    }

    public function setAccepted(?bool $accepted): self
    {
        $this->accepted = $accepted;

        return $this;
    }
}
