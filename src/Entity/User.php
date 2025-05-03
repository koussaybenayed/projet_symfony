<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[UniqueEntity(fields: ['user_username'], message: 'This username is already taken.')]
#[UniqueEntity(fields: ['user_email'], message: 'This email is already registered.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]

    private ?int $user_id = null;
    public function getUserFirstname(): ?string
    {
        return $this->user_firstname;
    }
    
    public function setUserFirstname(?string $user_firstname): self
    {
        $this->user_firstname = $user_firstname;
        return $this;
    }
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Please enter a username')]
    #[Assert\Length(min: 3, minMessage: 'Username must be at least {{ limit }} characters')]
    private ?string $user_username = null;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message: 'Please enter an email address')]
    #[Assert\Email(message: 'Please enter a valid email address')]
    private ?string $user_email = null;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message: 'Please enter a password')]
    #[Assert\Length(min: 6, minMessage: 'Password must be at least {{ limit }} characters')]
    private ?string $user_password = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $user_firstname = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $user_lastname = null;
   
    

    #[ORM\Column(type: 'date', nullable: true)]
    #[Assert\LessThan([
        'value' => 'today -18 years',
        'message' => 'You must be at least 18 years old.'
    ])]
    private ?\DateTimeInterface $user_birthday = null;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\Choice(choices: ['male', 'female'], message: 'Choose a valid gender')]
    private ?string $user_gender = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $user_picture = null;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\Regex(
        pattern: '/^\d+$/',
        message: 'Phone number should only contain digits'
    )]
    private ?string $user_phonenumber = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $user_level = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $user_role = null;

    #[ORM\ManyToOne(targetEntity: Role::class, inversedBy: 'users')]
    #[ORM\JoinColumn(name: 'role_id', referencedColumnName: 'role_id')]
    private ?Role $role = null;

    #[ORM\OneToMany(targetEntity: Billet::class, mappedBy: 'user')]
    private Collection $billets;

    #[ORM\OneToMany(targetEntity: Coli::class, mappedBy: 'user')]
    private Collection $colis;

    #[ORM\OneToMany(targetEntity: Facturisation::class, mappedBy: 'user')]
    private Collection $facturisations;

    #[ORM\OneToMany(targetEntity: Reclamation::class, mappedBy: 'user')]
    private Collection $reclamations;

   

    // For password hashing
    private ?string $plainPassword = null;

    public function __construct()
    {
        $this->billets = new ArrayCollection();
        $this->colis = new ArrayCollection();
        $this->facturisations = new ArrayCollection();
        $this->reclamations = new ArrayCollection();
    }
    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private bool $isActive = true;
    // Getters & Setters (shortened for clarity, expand as needed)
    public function getisActive(): bool
{
    return $this->isActive;
}

public function setIsActive(bool $isActive): self
{
    $this->isActive = $isActive;
    return $this;
}
    public function getUserId(): ?int { return $this->user_id; }

    public function getUserUsername(): ?string { return $this->user_username; }
    public function setUserUsername(string $user_username): self { $this->user_username = $user_username; return $this; }

    public function getUserEmail(): ?string { return $this->user_email; }
    public function setUserEmail(string $user_email): self { $this->user_email = $user_email; return $this; }

    public function getUserPassword(): ?string { return $this->user_password; }
    public function setUserPassword(string $user_password): self 
    { 
        $this->user_password = $user_password;
        return $this; 
    }

    public function getPlainPassword(): ?string { return $this->plainPassword; }
    public function setPlainPassword(?string $plainPassword): self { $this->plainPassword = $plainPassword; return $this; }

    public function eraseCredentials(): void { $this->plainPassword = null; }
    public function getSalt(): ?string { return null; }

    public function getRoles(): array { return ['ROLE_USER']; }
    public function getUserIdentifier(): string { return (string) $this->user_username; }
    public function getPassword(): ?string { return $this->user_password; }

    // Add remaining getters/setters similarly...

    // Example for associations:
    public function getBillets(): Collection { return $this->billets; }
    public function addBillet(Billet $billet): self {
        if (!$this->billets->contains($billet)) {
            $this->billets[] = $billet;
        }
        return $this;
    }
    public function removeBillet(Billet $billet): self {
        $this->billets->removeElement($billet);
        return $this;
    }

    // Repeat similarly for colis, facturisations, reclamations, reservations...
    
public function getUserLastname(): ?string
{
    return $this->user_lastname;
}

public function setUserLastname(?string $user_lastname): self
{
    $this->user_lastname = $user_lastname;
    return $this;
}

public function getUserBirthday(): ?\DateTimeInterface
{
    return $this->user_birthday;
}

public function setUserBirthday(?\DateTimeInterface $user_birthday): self
{
    $this->user_birthday = $user_birthday;
    return $this;
}

public function getUserGender(): ?string
{
    return $this->user_gender;
}

public function setUserGender(?string $user_gender): self
{
    $this->user_gender = $user_gender;
    return $this;
}

public function getUserPicture(): ?string
{
    return $this->user_picture;
}

public function setUserPicture(?string $user_picture): self
{
    $this->user_picture = $user_picture;
    return $this;
}

public function getUserPhonenumber(): ?string
{
    return $this->user_phonenumber;
}

public function setUserPhonenumber(?string $user_phonenumber): self
{
    $this->user_phonenumber = $user_phonenumber;
    return $this;
}

public function getUserLevel(): ?int
{
    return $this->user_level;
}

public function setUserLevel(?int $user_level): self
{
    $this->user_level = $user_level;
    return $this;
}

public function getUserRole(): ?string
{
    return $this->user_role;
}

public function setUserRole(?string $user_role): self
{
    $this->user_role = $user_role;
    return $this;
}

public function getRole(): ?Role
{
    return $this->role;
}

public function setRole(?Role $role): self
{
    $this->role = $role;
    return $this;
}

public function getColis(): Collection
{
    return $this->colis;
}

public function addColi(Coli $coli): self
{
    if (!$this->colis->contains($coli)) {
        $this->colis[] = $coli;
    }
    return $this;
}

public function removeColi(Coli $coli): self
{
    $this->colis->removeElement($coli);
    return $this;
}

public function getFacturisations(): Collection
{
    return $this->facturisations;
}

public function addFacturisation(Facturisation $facturisation): self
{
    if (!$this->facturisations->contains($facturisation)) {
        $this->facturisations[] = $facturisation;
    }
    return $this;
}

public function removeFacturisation(Facturisation $facturisation): self
{
    $this->facturisations->removeElement($facturisation);
    return $this;
}

public function getReclamations(): Collection
{
    return $this->reclamations;
}

public function addReclamation(Reclamation $reclamation): self
{
    if (!$this->reclamations->contains($reclamation)) {
        $this->reclamations[] = $reclamation;
    }
    return $this;
}

public function removeReclamation(Reclamation $reclamation): self
{
    $this->reclamations->removeElement($reclamation);
    return $this;
}


}
