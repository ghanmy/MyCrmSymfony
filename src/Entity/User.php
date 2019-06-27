<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $nom;
    /**
     * @ORM\Column(type="string", length=191,nullable=false)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=191,nullable=false)
     * @Assert\Regex(pattern="/^[0-9]{8}$/", message="Le numéro de téléphone doit contenir 8 chiffres")
     */
    private $tel1;
    /**
     * @ORM\Column(type="string", length=191,nullable=true)
     * @Assert\Regex(pattern="/^[0-9]{8}$/", message="Le numéro de téléphone doit contenir 8 chiffres")
     */
    private $tel2;

    /**
     * @ORM\Column(type="string", length=191, nullable=true, options={"default" : "Tunisie"})
     */
    private $pays;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=191,nullable=false,unique=true)
     * @Assert\Email(
     *     message = "Le mail '{{ value }}' n'est pas email valide.",
     *     checkMX = true
     * )
     *
     */
    private $email;

    /**
     * @var array
     * @ORM\Column(type="array")
     */
    private $roles;
    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var string le token qui servira lors de l'oubli de mot de passe
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $resetToken;

    public function __construct() {
        $this->isActive = true;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid('', true));
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }
    public function getTel1(): ?string
    {
        return $this->tel1;
    }

    public function setTel1(string $tel1): self
    {
        $this->tel1 = $tel1;

        return $this;
    }

    public function getTel2(): ?string
    {
        return $this->tel2;
    }

    public function setTel2(string $tel2): self
    {
        $this->tel2 = $tel2;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays ="Tunisie"): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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

    public function getIsActive(){
        return $this->isActive;
    }

    public function setIsActive($isActive){
        $this->isActive = $isActive;
        return $this;
    }

    public function getUsername() {
        return $this->email;
    }
    public function getRoles() {
        if (empty($this->roles)) {
            return ['ROLE_USER'];
        }
        return $this->roles;
    }

    public function setRoles(array $roles)
    {
        /*if (!in_array('ROLE_USER', $roles))
        {
            $roles[] = 'ROLE_USER';
        }*/
        foreach ($roles as $role)
        {
            if(substr($role, 0, 5) !== 'ROLE_') {
                throw new InvalidArgumentException("Chaque rôle doit commencer par 'ROLE_'");
            }
        }
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return string
     */
    public function getResetToken(): string
    {
        return $this->resetToken;
    }

    /**
     * @param string $resetToken
     */
    public function setResetToken(?string $resetToken): void
    {
        $this->resetToken = $resetToken;
    }

    public function eraseCredentials() {

    }
    public function getSalt() {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }
}
