<?php

namespace App\Entity;

use Datetime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProspectsRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Prospect
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Email(
     *     message = "L'email '{{ value }}' n'est pas un email valide."
     *   )
     */

    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank()
     */
    private $tel;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $tvacode;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Contact",mappedBy="prospect",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $contacts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Calls", mappedBy="prospect",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $calls;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Appointment", mappedBy="prospect",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $appointments;

    /**
     * @ORM\Column(name="createdat", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(name="updatetat", type="datetime",nullable=true)
     */
    private $updatedAt;

    public function __construct()
    {
        $this->createdAt = new Datetime();
        $this->contacts = new ArrayCollection();
        $this->calls = new ArrayCollection();
        $this->appointments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getTvacode(): ?string
    {
        return $this->tvacode;
    }

    public function setTvacode(?string $tvacode): self
    {
        $this->tvacode = $tvacode;

        return $this;
    }

    public function setTel(?string $tel)
    {
        $this->tel = $tel;

        return $this;
    }

    public function getTel()
    {
        return $this->tel;
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

    public function setUpdatedAt(?\Datetime $updatedAt)
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


    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getContacts()
    {
        return $this->contacts;
    }


    public function addContact(Contact $contact)
    {
        $this->contacts->add($contact);
        $contact->setProspect($this);
    }


    public function removeContact(Contact $contact)
    {
        $this->contacts->removeElement($contact);
    }

    public function getCalls()
    {
        return $this->calls;
    }

    public function addCall ($call)
    {
        $this->calls->add($call);
        $call->setProspect($this);
    }


    public function removeCall(Calls $call)
    {
        $this->calls->removeElement($call);
    }

    public function getAppointment()
    {
        return $this->appointment;
    }

    public function addAppointment ($appointment)
    {
        $this->appointment->add($appointment);
        $appointment->setProspect($this);
    }

    public function removeAppointment(Appointment $appointment)
    {
        $this->appointment->removeElement($appointment);
    }
}
