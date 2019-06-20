<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AppointmentRepository")
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
     * @ORM\Column(type="datetime")
     */
    private $date;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Prospect")
     * @ORM\JoinColumn(nullable=false)
     */
    private $prospect;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contact")
     * @ORM\JoinColumn(nullable=false)
     */
    private $contact;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getContact()
    {
        return $this->contact;
    }
    public function setContact(Contact $contact)
    {
        $this->contact = $contact;
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
}
