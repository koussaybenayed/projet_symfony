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

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $date_arrive = null;

    public function getDate_arrive(): ?\DateTimeInterface
    {
        return $this->date_arrive;
    }

    public function setDate_arrive(\DateTimeInterface $date_arrive): self
    {
        $this->date_arrive = $date_arrive;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $seat_number = null;

    public function getSeat_number(): ?int
    {
        return $this->seat_number;
    }

    public function setSeat_number(int $seat_number): self
    {
        $this->seat_number = $seat_number;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $type_billet = null;

    public function getType_billet(): ?string
    {
        return $this->type_billet;
    }

    public function setType_billet(string $type_billet): self
    {
        $this->type_billet = $type_billet;
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

    #[ORM\Column(type: 'integer', nullable: false)]
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

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $trajet_id = null;

    public function getTrajet_id(): ?int
    {
        return $this->trajet_id;
    }

    public function setTrajet_id(int $trajet_id): self
    {
        $this->trajet_id = $trajet_id;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $reduction_id = null;

    public function getReduction_id(): ?int
    {
        return $this->reduction_id;
    }

    public function setReduction_id(int $reduction_id): self
    {
        $this->reduction_id = $reduction_id;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $category = null;

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $flight_number = null;

    public function getFlight_number(): ?int
    {
        return $this->flight_number;
    }

    public function setFlight_number(?int $flight_number): self
    {
        $this->flight_number = $flight_number;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $airline = null;

    public function getAirline(): ?string
    {
        return $this->airline;
    }

    public function setAirline(?string $airline): self
    {
        $this->airline = $airline;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $boat_name = null;

    public function getBoat_name(): ?string
    {
        return $this->boat_name;
    }

    public function setBoat_name(?string $boat_name): self
    {
        $this->boat_name = $boat_name;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $cabin_type = null;

    public function getCabin_type(): ?string
    {
        return $this->cabin_type;
    }

    public function setCabin_type(?string $cabin_type): self
    {
        $this->cabin_type = $cabin_type;
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

    public function getDateArrive(): ?\DateTimeInterface
    {
        return $this->date_arrive;
    }

    public function setDateArrive(\DateTimeInterface $date_arrive): static
    {
        $this->date_arrive = $date_arrive;

        return $this;
    }

    public function getSeatNumber(): ?int
    {
        return $this->seat_number;
    }

    public function setSeatNumber(int $seat_number): static
    {
        $this->seat_number = $seat_number;

        return $this;
    }

    public function getTypeBillet(): ?string
    {
        return $this->type_billet;
    }

    public function setTypeBillet(string $type_billet): static
    {
        $this->type_billet = $type_billet;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getTrajetId(): ?int
    {
        return $this->trajet_id;
    }

    public function setTrajetId(int $trajet_id): static
    {
        $this->trajet_id = $trajet_id;

        return $this;
    }

    public function getReductionId(): ?int
    {
        return $this->reduction_id;
    }

    public function setReductionId(int $reduction_id): static
    {
        $this->reduction_id = $reduction_id;

        return $this;
    }

    public function getFlightNumber(): ?int
    {
        return $this->flight_number;
    }

    public function setFlightNumber(?int $flight_number): static
    {
        $this->flight_number = $flight_number;

        return $this;
    }

    public function getBoatName(): ?string
    {
        return $this->boat_name;
    }

    public function setBoatName(?string $boat_name): static
    {
        $this->boat_name = $boat_name;

        return $this;
    }

    public function getCabinType(): ?string
    {
        return $this->cabin_type;
    }

    public function setCabinType(?string $cabin_type): static
    {
        $this->cabin_type = $cabin_type;

        return $this;
    }

}
