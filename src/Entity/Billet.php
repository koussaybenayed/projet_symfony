<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\BilletRepository;

#[ORM\Entity(repositoryClass: BilletRepository::class)]
#[ORM\Table(name: 'billets')]
class Billet
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

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $date_depart = null;

    public function getDate_depart(): ?\DateTimeInterface
    {
        return $this->date_depart;
    }

    public function setDate_depart(?\DateTimeInterface $date_depart): self
    {
        $this->date_depart = $date_depart;
        return $this;
    }

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $date_arrive = null;

    public function getDate_arrive(): ?\DateTimeInterface
    {
        return $this->date_arrive;
    }

    public function setDate_arrive(?\DateTimeInterface $date_arrive): self
    {
        $this->date_arrive = $date_arrive;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'billets')]
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

    #[ORM\ManyToOne(targetEntity: Trajet::class, inversedBy: 'billets')]
    #[ORM\JoinColumn(name: 'trajet_id', referencedColumnName: 'id')]
    private ?Trajet $trajet = null;

    public function getTrajet(): ?Trajet
    {
        return $this->trajet;
    }

    public function setTrajet(?Trajet $trajet): self
    {
        $this->trajet = $trajet;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Reduction::class, inversedBy: 'billets')]
    #[ORM\JoinColumn(name: 'reduction_id', referencedColumnName: 'id')]
    private ?Reduction $reduction = null;

    public function getReduction(): ?Reduction
    {
        return $this->reduction;
    }

    public function setReduction(?Reduction $reduction): self
    {
        $this->reduction = $reduction;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $type_transport = null;

    public function getType_transport(): ?string
    {
        return $this->type_transport;
    }

    public function setType_transport(?string $type_transport): self
    {
        $this->type_transport = $type_transport;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $compagnie = null;

    public function getCompagnie(): ?string
    {
        return $this->compagnie;
    }

    public function setCompagnie(?string $compagnie): self
    {
        $this->compagnie = $compagnie;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $depart = null;

    public function getDepart(): ?string
    {
        return $this->depart;
    }

    public function setDepart(?string $depart): self
    {
        $this->depart = $depart;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $destination = null;

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(?string $destination): self
    {
        $this->destination = $destination;
        return $this;
    }

    #[ORM\Column(type: 'decimal', nullable: true)]
    private ?float $prix = null;

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $classe = null;

    public function getClasse(): ?string
    {
        return $this->classe;
    }

    public function setClasse(?string $classe): self
    {
        $this->classe = $classe;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $statut = null;

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: Assistance::class, mappedBy: 'billet')]
    private Collection $assistances;

    /**
     * @return Collection<int, Assistance>
     */
    public function getAssistances(): Collection
    {
        if (!$this->assistances instanceof Collection) {
            $this->assistances = new ArrayCollection();
        }
        return $this->assistances;
    }

    public function addAssistance(Assistance $assistance): self
    {
        if (!$this->getAssistances()->contains($assistance)) {
            $this->getAssistances()->add($assistance);
        }
        return $this;
    }

    public function removeAssistance(Assistance $assistance): self
    {
        $this->getAssistances()->removeElement($assistance);
        return $this;
    }

    #[ORM\OneToMany(targetEntity: Facturisation::class, mappedBy: 'billet')]
    private Collection $facturisations;

    public function __construct()
    {
        $this->assistances = new ArrayCollection();
        $this->facturisations = new ArrayCollection();
    }

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

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->date_depart;
    }

    public function setDateDepart(?\DateTimeInterface $date_depart): static
    {
        $this->date_depart = $date_depart;

        return $this;
    }

    public function getDateArrive(): ?\DateTimeInterface
    {
        return $this->date_arrive;
    }

    public function setDateArrive(?\DateTimeInterface $date_arrive): static
    {
        $this->date_arrive = $date_arrive;

        return $this;
    }

    public function getTypeTransport(): ?string
    {
        return $this->type_transport;
    }

    public function setTypeTransport(?string $type_transport): static
    {
        $this->type_transport = $type_transport;

        return $this;
    }

}
