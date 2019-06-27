<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AppointmentRepository")
 * @ORM\HasLifecycleCallbacks()
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
     * @ORM\Column(name="meeting_date",type="datetime")
     */
    private $meetingDate;

    /**
     * @JMS\Type("DateTime<'H:i'>")
     * @ORM\Column(name="meeting_time", type="time")
     */
    private $meetingTime;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $meetingPlace;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Prospect", inversedBy="appointments")
     * @ORM\JoinColumn(referencedColumnName="id",nullable=false,onDelete="CASCADE")
     */
    private $prospect;

    /**
     * @ORM\JoinColumn(name="calls_id", nullable=true)
     * @ORM\OneToOne(targetEntity="App\Entity\Calls", inversedBy="appointment")
     */
    private $call;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="commercial_id", referencedColumnName="id")
     */
    private $commercial;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(name="createdat", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(name="updatedat", type="datetime", nullable=true)
     */
    private $updatedAt;

    public function __construct()
    {
        $this->createdAt = new Datetime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMeetingDate(): ?\DateTimeInterface
    {
        return $this->meetingDate;
    }

    public function setMeetingDate(\DateTimeInterface $meetingDate): self
    {
        $this->meetingDate = $meetingDate;

        return $this;
    }

    public function getMeetingPlace(): ?string
    {
        return $this->meetingPlace;
    }

    public function setMeetingPlace(string $meetingPlace): self
    {
        $this->meetingPlace = $meetingPlace;

        return $this;
    }

    public function setMeetingTime($meetingTime): self
    {
        $this->meetingTime = $meetingTime;

        return $this;
    }

    public function getMeetingTime()
    {
        return $this->meetingTime;
    }

    public function setProspect(Prospect $prospect)
    {
        $this->prospect = $prospect;

        return $this;
    }

    public function getProspect()
    {
        return $this->prospect;
    }

    public function getCall()
    {
        return $this->call;
    }

    public function setCall(Calls $call=NULL)
    {
        $this->call = $call;
        return $this;
    }

    public function getCommercial()
    {
        return $this->commercial;
    }

    public function setCommercial(User $commercial)
    {
        $this->commercial = $commercial;
        return $this;
    }

    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setCreatedAt(Datetime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(Datetime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setUpdatedAt(new \Datetime());
    }
}
