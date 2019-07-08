<?php

namespace App\Entity;

use Datetime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 */
class Contact
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Prospect",inversedBy="contacts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $prospect;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Email(
     *     message = "L'email '{{ value }}' n'est pas un email valide."
     *   )
     */

    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */

    private $adress;
    /**
     * @ORM\Column(type="string", length=20)
     */
    private $sex;
    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank()
     */
    private $telbureau;
    /**
     * @ORM\Column(type="string", length=20)
     */
    private $telmobile1;
    /**
     * @ORM\Column(type="string", length=20)
     */
    private $telmobile2;
    /**
     * @ORM\Column(name="createdat", type="datetime")
     */
    private $createdat;

    public function __construct()
    {
        $this->createdat = new Datetime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(?string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(?string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function setTelBureau(?string $telbureau)
    {
        $this->telbureau = $telbureau;

        return $this;
    }

    public function getTelBureau()
    {
        return $this->telbureau;
    }

    public function setTelMobile1(?string $telmobile1)
    {
        $this->telmobile1 = $telmobile1;

        return $this;
    }

    public function getTelMobile1()
    {
        return $this->telmobile1;
    }

    public function setTelMobile2(?string $telmobile2)
    {
        $this->telmobile2 = $telmobile2;

        return $this;
    }

    public function getTelMobile2()
    {
        return $this->telmobile2;
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

    public function setProspect(Prospect $prospect)
    {
        $this->prospect = $prospect;

        return $this;
    }

    public function getProspect()
    {
        return $this->prospect;
    }
}
