<?php

namespace App\Entity;
use App\Entity\Billet;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AssistanceRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AssistanceRepository::class)]
#[ORM\Table(name: 'assistance')]
class Assistance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: Billet::class, inversedBy: 'assistance')]
    #[ORM\JoinColumn(name: 'billet_id', referencedColumnName: 'id', unique: true)]
    #[Assert\NotNull(message: 'Le billet associé est requis.')]
    private ?Billet $billet = null;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\NotBlank(message: 'Le type d’assistance est requis.')]
    private ?string $typeAssistance = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: 'L’aéroport ou port est requis.')]
    private ?string $aeroportPort = null;

    #[ORM\Column(type: 'datetime', nullable: false)]
    #[Assert\NotBlank(message: 'L’heure de prise en charge est requise.')]
    #[Assert\Type(\DateTimeInterface::class)]
    #[Assert\GreaterThanOrEqual('today', message: 'La date doit être aujourd’hui ou plus tard.')]
    private ?\DateTimeInterface $heurePriseEnCharge = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: 'Le point de rendez-vous est requis.')]
    private ?string $pointRendezVous = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: 'Les informations complémentaires sont requises.')]
    private ?string $informationsComplementaires = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Assert\NotBlank(message: 'Le statut est requis.')]
    private ?string $statut = null;

    // Getters & Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getBillet(): ?Billet
    {
        return $this->billet;
    }

    public function setBillet(?Billet $billet): self
    {
        $this->billet = $billet;
        return $this;
    }

    public function getTypeAssistance(): ?string
    {
        return $this->typeAssistance;
    }

    public function setTypeAssistance(?string $typeAssistance): self
    {
        $this->typeAssistance = $typeAssistance;
        return $this;
    }

    public function getAeroportPort(): ?string
    {
        return $this->aeroportPort;
    }

    public function setAeroportPort(?string $aeroportPort): self
    {
        $this->aeroportPort = $aeroportPort;
        return $this;
    }

    public function getHeurePriseEnCharge(): ?\DateTimeInterface
    {
        return $this->heurePriseEnCharge;
    }

    public function setHeurePriseEnCharge(?\DateTimeInterface $heurePriseEnCharge): self
    {
        $this->heurePriseEnCharge = $heurePriseEnCharge;
        return $this;
    }

    public function getPointRendezVous(): ?string
    {
        return $this->pointRendezVous;
    }

    public function setPointRendezVous(?string $pointRendezVous): self
    {
        $this->pointRendezVous = $pointRendezVous;
        return $this;
    }

    public function getInformationsComplementaires(): ?string
    {
        return $this->informationsComplementaires;
    }

    public function setInformationsComplementaires(?string $informationsComplementaires): self
    {
        $this->informationsComplementaires = $informationsComplementaires;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }
}
