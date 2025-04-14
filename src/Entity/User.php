<?php

namespace App\Entity;

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
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $fullname = null;

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $email = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $password = null;

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $phone = null;

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $role = null;

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $created_at = null;

    public function getCreated_at(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreated_at(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;
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

    #[ORM\OneToMany(targetEntity: Paiement::class, mappedBy: 'user')]
    private Collection $paiements;

    /**
     * @return Collection<int, Paiement>
     */
    public function getPaiements(): Collection
    {
        if (!$this->paiements instanceof Collection) {
            $this->paiements = new ArrayCollection();
        }
        return $this->paiements;
    }

    public function addPaiement(Paiement $paiement): self
    {
        if (!$this->getPaiements()->contains($paiement)) {
            $this->getPaiements()->add($paiement);
        }
        return $this;
    }

    public function removePaiement(Paiement $paiement): self
    {
        $this->getPaiements()->removeElement($paiement);
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

}
