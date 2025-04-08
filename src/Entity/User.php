<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\UserRepository;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $user_id = null;

    public function getUser_id(): ?int
    {
        return $this->user_id;
    }

    public function setUser_id(int $user_id): self
    {
        $this->user_id = $user_id;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $user_username = null;

    public function getUser_username(): ?string
    {
        return $this->user_username;
    }

    public function setUser_username(string $user_username): self
    {
        $this->user_username = $user_username;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $user_email = null;

    public function getUser_email(): ?string
    {
        return $this->user_email;
    }

    public function setUser_email(string $user_email): self
    {
        $this->user_email = $user_email;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $user_password = null;

    public function getUser_password(): ?string
    {
        return $this->user_password;
    }

    public function setUser_password(string $user_password): self
    {
        $this->user_password = $user_password;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $user_firstname = null;

    public function getUser_firstname(): ?string
    {
        return $this->user_firstname;
    }

    public function setUser_firstname(?string $user_firstname): self
    {
        $this->user_firstname = $user_firstname;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $user_lastname = null;

    public function getUser_lastname(): ?string
    {
        return $this->user_lastname;
    }

    public function setUser_lastname(?string $user_lastname): self
    {
        $this->user_lastname = $user_lastname;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $user_birthday = null;

    public function getUser_birthday(): ?\DateTimeInterface
    {
        return $this->user_birthday;
    }

    public function setUser_birthday(?\DateTimeInterface $user_birthday): self
    {
        $this->user_birthday = $user_birthday;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $user_gender = null;

    public function getUser_gender(): ?string
    {
        return $this->user_gender;
    }

    public function setUser_gender(?string $user_gender): self
    {
        $this->user_gender = $user_gender;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $user_picture = null;

    public function getUser_picture(): ?string
    {
        return $this->user_picture;
    }

    public function setUser_picture(?string $user_picture): self
    {
        $this->user_picture = $user_picture;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $user_phonenumber = null;

    public function getUser_phonenumber(): ?string
    {
        return $this->user_phonenumber;
    }

    public function setUser_phonenumber(?string $user_phonenumber): self
    {
        $this->user_phonenumber = $user_phonenumber;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $user_level = null;

    public function getUser_level(): ?int
    {
        return $this->user_level;
    }

    public function setUser_level(?int $user_level): self
    {
        $this->user_level = $user_level;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $user_role = null;

    public function getUser_role(): ?string
    {
        return $this->user_role;
    }

    public function setUser_role(?string $user_role): self
    {
        $this->user_role = $user_role;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Role::class, inversedBy: 'users')]
    #[ORM\JoinColumn(name: 'role_id', referencedColumnName: 'role_id')]
    private ?Role $role = null;

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: Billet::class, mappedBy: 'user')]
    private Collection $billets;

    /**
     * @return Collection<int, Billet>
     */
    public function getBillets(): Collection
    {
        if (!$this->billets instanceof Collection) {
            $this->billets = new ArrayCollection();
        }
        return $this->billets;
    }

    public function addBillet(Billet $billet): self
    {
        if (!$this->getBillets()->contains($billet)) {
            $this->getBillets()->add($billet);
        }
        return $this;
    }

    public function removeBillet(Billet $billet): self
    {
        $this->getBillets()->removeElement($billet);
        return $this;
    }

    #[ORM\OneToMany(targetEntity: Coli::class, mappedBy: 'user')]
    private Collection $colis;

    /**
     * @return Collection<int, Coli>
     */
    public function getColis(): Collection
    {
        if (!$this->colis instanceof Collection) {
            $this->colis = new ArrayCollection();
        }
        return $this->colis;
    }

    public function addColi(Coli $coli): self
    {
        if (!$this->getColis()->contains($coli)) {
            $this->getColis()->add($coli);
        }
        return $this;
    }

    public function removeColi(Coli $coli): self
    {
        $this->getColis()->removeElement($coli);
        return $this;
    }

#[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'facturisations')]
#[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'user_id')]
private ?User $user = null;
#[ORM\OneToMany(targetEntity: Facturisation::class, mappedBy: 'user')]
    private Collection $facturisations;

    /**
     * @return Collection<int, Facturisation>
     */
    public function getFacturisations(): Collection
    {
        if (!$this->facturisations instanceof Collection) {
            $this->facturisations = new ArrayCollection();
        }
        return $this->facturisations;
    }

    public function addFacturisation(Facturisation $facturisation): self
    {
        if (!$this->getFacturisations()->contains($facturisation)) {
            $this->getFacturisations()->add($facturisation);
        }
        return $this;
    }

    public function removeFacturisation(Facturisation $facturisation): self
    {
        $this->getFacturisations()->removeElement($facturisation);
        return $this;
    }

    #[ORM\OneToMany(targetEntity: Reclamation::class, mappedBy: 'user')]
    private Collection $reclamations;

    /**
     * @return Collection<int, Reclamation>
     */
    public function getReclamations(): Collection
    {
        if (!$this->reclamations instanceof Collection) {
            $this->reclamations = new ArrayCollection();
        }
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): self
    {
        if (!$this->getReclamations()->contains($reclamation)) {
            $this->getReclamations()->add($reclamation);
        }
        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): self
    {
        $this->getReclamations()->removeElement($reclamation);
        return $this;
    }

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'user')]
    private Collection $reservations;

    public function __construct()
    {
        $this->billets = new ArrayCollection();
        $this->colis = new ArrayCollection();
        $this->facturisations = new ArrayCollection();
        $this->reclamations = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        if (!$this->reservations instanceof Collection) {
            $this->reservations = new ArrayCollection();
        }
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->getReservations()->contains($reservation)) {
            $this->getReservations()->add($reservation);
        }
        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        $this->getReservations()->removeElement($reservation);
        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function getUserUsername(): ?string
    {
        return $this->user_username;
    }

    public function setUserUsername(string $user_username): static
    {
        $this->user_username = $user_username;

        return $this;
    }

    public function getUserEmail(): ?string
    {
        return $this->user_email;
    }

    public function setUserEmail(string $user_email): static
    {
        $this->user_email = $user_email;

        return $this;
    }

    public function getUserPassword(): ?string
    {
        return $this->user_password;
    }

    public function setUserPassword(string $user_password): static
    {
        $this->user_password = $user_password;

        return $this;
    }

    public function getUserFirstname(): ?string
    {
        return $this->user_firstname;
    }

    public function setUserFirstname(?string $user_firstname): static
    {
        $this->user_firstname = $user_firstname;

        return $this;
    }

    public function getUserLastname(): ?string
    {
        return $this->user_lastname;
    }

    public function setUserLastname(?string $user_lastname): static
    {
        $this->user_lastname = $user_lastname;

        return $this;
    }

    public function getUserBirthday(): ?\DateTimeInterface
    {
        return $this->user_birthday;
    }

    public function setUserBirthday(?\DateTimeInterface $user_birthday): static
    {
        $this->user_birthday = $user_birthday;

        return $this;
    }

    public function getUserGender(): ?string
    {
        return $this->user_gender;
    }

    public function setUserGender(?string $user_gender): static
    {
        $this->user_gender = $user_gender;

        return $this;
    }

    public function getUserPicture(): ?string
    {
        return $this->user_picture;
    }

    public function setUserPicture(?string $user_picture): static
    {
        $this->user_picture = $user_picture;

        return $this;
    }

    public function getUserPhonenumber(): ?string
    {
        return $this->user_phonenumber;
    }

    public function setUserPhonenumber(?string $user_phonenumber): static
    {
        $this->user_phonenumber = $user_phonenumber;

        return $this;
    }

    public function getUserLevel(): ?int
    {
        return $this->user_level;
    }

    public function setUserLevel(?int $user_level): static
    {
        $this->user_level = $user_level;

        return $this;
    }

    public function getUserRole(): ?string
    {
        return $this->user_role;
    }

    public function setUserRole(?string $user_role): static
    {
        $this->user_role = $user_role;

        return $this;
    }

}
