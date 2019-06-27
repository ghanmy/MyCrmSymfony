<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CallsRepository")
 */
class Calls
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Prospect",inversedBy="calls")
     * @ORM\JoinColumn(nullable=false)
     */
    private $prospect;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contact")
     * @ORM\JoinColumn(nullable=true)
     */
    private $contact;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * One Call has One Appointment.
     * @ORM\OneToOne(targetEntity="App\Entity\Appointment", mappedBy="call", cascade={"persist", "remove"})
     */
    private $appointment;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @JMS\Type("DateTime<'H:i'>")
     * @ORM\Column(name="call_time", type="time")
     */
    private $callTime;
    /**
     * @ORM\Column(type="integer")
     */
    private $duration;
    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $nextCallDate;
    /**
     * @JMS\Type("DateTime<'H:i'>")
     * @ORM\Column(name="next_call_time", type="time",nullable=true)
     */
    private $nextCallTime;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $subject;

    /**
     * @ORM\Column(name="calltype", type="boolean")//true sortie - false:sortie
     */
    private $calltype;

    /**
     * @ORM\Column(name="createdat", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(name="updatedat", type="datetime",nullable=true)
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function setCallTime($callTime): self
    {
        $this->callTime = $callTime;

        return $this;
    }

    public function getCallTime()
    {
        return $this->callTime;
    }

    public function getNextCallDate(): ?\DateTimeInterface
    {
        return $this->nextCallDate;
    }

    public function setNextCallDate(?\DateTimeInterface $nextCallDate): self
    {
        $this->nextCallDate = $nextCallDate;

        return $this;
    }

    public function setNextCallTime($nextCallTime = NULL): self
    {
        $this->nextCallTime = $nextCallTime;

        return $this;
    }

    public function getNextCallTime()
    {
        return $this->nextCallTime;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function setDuration($duration): self
    {
        $this->duration = $duration;

        return $this;
    }
    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): self
    {
        $this->comments = $comments;

        return $this;
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

    public function getAppointment()
    {
        return $this->appointment;
    }

    public function setAppointment (Appointment $appointment)
    {
        $this->appointment->$appointment;
        return $appointment;

    }

    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getCallType(){
        return $this->calltype;
    }

    public function setCallType($calltype){
        $this->calltype = $calltype;
        return $this;
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
