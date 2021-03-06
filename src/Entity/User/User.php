<?php

namespace App\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity(repositoryClass="App\Repository\User\UserRepository")
 * @ORM\Table(name="fos_user")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="userType", type="string", length=20)
 *
 * @Serializer\ExclusionPolicy("all")
 */
abstract class User extends BaseUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Serializer\Expose
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=45)
     * @Serializer\Expose
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=45)
     * @Serializer\Expose
     */
    protected $surname;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\User\Notification", mappedBy="user", orphanRemoval=true)
     */
    protected $notifications;

    public function __construct()
    {
        parent::__construct();
        $this->notifications = new ArrayCollection();
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getFullName(): ?string
    {
        return sprintf('%s %s', $this->firstname, $this->surname);
    }

    /**
     * @return Collection|Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setUser($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            // set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }

        return $this;
    }
}
