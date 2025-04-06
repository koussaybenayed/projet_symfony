<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\TrajetRepository;

#[ORM\Entity(repositoryClass: TrajetRepository::class)]
#[ORM\Table(name: 'trajets')]
class Trajet
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
    private ?string $departure = null;

    public function getDeparture(): ?string
    {
        return $this->departure;
    }

    public function setDeparture(string $departure): self
    {
        $this->departure = $departure;
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

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $date_depart = null;

    public function getDate_depart(): ?\DateTimeInterface
    {
        return $this->date_depart;
    }

    public function setDate_depart(\DateTimeInterface $date_depart): self
    {
        $this->date_depart = $date_depart;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $transport_type = null;

    public function getTransport_type(): ?string
    {
        return $this->transport_type;
    }

    public function setTransport_type(string $transport_type): self
    {
        $this->transport_type = $transport_type;
        return $this;
    }

    #[ORM\Column(type: 'decimal', nullable: false)]
    private ?float $price = null;

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    #[ORM\Column(type: 'decimal', nullable: true)]
    private ?float $distance = null;

    public function getDistance(): ?float
    {
        return $this->distance;
    }

    public function setDistance(?float $distance): self
    {
        $this->distance = $distance;
        return $this;
    }

    #[ORM\Column(type: 'decimal', nullable: true)]
    private ?float $estimated_time = null;

    public function getEstimated_time(): ?float
    {
        return $this->estimated_time;
    }

    public function setEstimated_time(?float $estimated_time): self
    {
        $this->estimated_time = $estimated_time;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: Billet::class, mappedBy: 'trajet')]
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

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'trajet')]
    private Collection $reservations;

    public function __construct()
    {
        $this->billets = new ArrayCollection();
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

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->date_depart;
    }

    public function setDateDepart(\DateTimeInterface $date_depart): static
    {
        $this->date_depart = $date_depart;

        return $this;
    }

    public function getTransportType(): ?string
    {
        return $this->transport_type;
    }

    public function setTransportType(string $transport_type): static
    {
        $this->transport_type = $transport_type;

        return $this;
    }

    public function getEstimatedTime(): ?string
    {
        return $this->estimated_time;
    }

    public function setEstimatedTime(?string $estimated_time): static
    {
        $this->estimated_time = $estimated_time;

        return $this;
    }

}
