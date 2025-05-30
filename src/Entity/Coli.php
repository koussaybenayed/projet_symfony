<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\ColiRepository;

#[ORM\Entity(repositoryClass: ColiRepository::class)]
#[ORM\Table(name: 'colis')]
class Coli
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

    #[ORM\Column(type: 'decimal', nullable: false)]
    private ?float $weight = null;

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $destination = null;

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): self
    {
        $this->destination = $destination;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $status = null;

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
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

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $tracking_number = null;

    public function getTracking_number(): ?int
    {
        return $this->tracking_number;
    }

    public function setTracking_number(int $tracking_number): self
    {
        $this->tracking_number = $tracking_number;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'colis')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'user_id')]
    private ?User $user = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: Facturisation::class, mappedBy: 'coli')]
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

    #[ORM\OneToOne(targetEntity: Livraison::class, mappedBy: 'coli')]
    private ?Livraison $livraison = null;

    public function __construct()
    {
        $this->facturisations = new ArrayCollection();
    }

    public function getLivraison(): ?Livraison
    {
        return $this->livraison;
    }

    public function setLivraison(?Livraison $livraison): self
    {
        $this->livraison = $livraison;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getTrackingNumber(): ?int
    {
        return $this->tracking_number;
    }

    public function setTrackingNumber(int $tracking_number): static
    {
        $this->tracking_number = $tracking_number;

        return $this;
    }

}
