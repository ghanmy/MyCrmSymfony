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
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $tvacode;

    /**
     * @ORM\Column(name="createdat", type="datetime")
     */
    private $createdat;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Contact",mappedBy="prospect",cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $contacts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Calls", mappedBy="prospect")
     */
    private $calls;

    public function __construct()
    {
        $this->createdat = new Datetime();
        $this->contacts = new ArrayCollection();
        $this->calls = new ArrayCollection();
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

    public function setCreatedat(Datetime $createdat)
    {
        $this->createdat = $createdat;

        return $this;
    }

    public function getCreatedat()
    {
        return $this->createdat;
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
}
