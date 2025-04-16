<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\AssistanceRepository;

#[ORM\Entity(repositoryClass: AssistanceRepository::class)]
#[ORM\Table(name: 'assistance')]
class Assistance
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

    #[ORM\OneToOne(targetEntity: Billet::class, inversedBy: 'assistance')]
    #[ORM\JoinColumn(name: 'billet_id', referencedColumnName: 'id', unique: true)]
    private ?Billet $billet = null;

    public function getBillet(): ?Billet
    {
        return $this->billet;
    }

    public function setBillet(?Billet $billet): self
    {
        $this->billet = $billet;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $type_assistance = null;

    public function getType_assistance(): ?string
    {
        return $this->type_assistance;
    }

    public function setType_assistance(?string $type_assistance): self
    {
        $this->type_assistance = $type_assistance;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $aeroport_port = null;

    public function getAeroport_port(): ?string
    {
        return $this->aeroport_port;
    }

    public function setAeroport_port(?string $aeroport_port): self
    {
        $this->aeroport_port = $aeroport_port;
        return $this;
    }

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $heure_prise_en_charge = null;

    public function getHeure_prise_en_charge(): ?\DateTimeInterface
    {
        return $this->heure_prise_en_charge;
    }

    public function setHeure_prise_en_charge(?\DateTimeInterface $heure_prise_en_charge): self
    {
        $this->heure_prise_en_charge = $heure_prise_en_charge;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $point_rendez_vous = null;

    public function getPoint_rendez_vous(): ?string
    {
        return $this->point_rendez_vous;
    }

    public function setPoint_rendez_vous(?string $point_rendez_vous): self
    {
        $this->point_rendez_vous = $point_rendez_vous;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $informations_complementaires = null;

    public function getInformations_complementaires(): ?string
    {
        return $this->informations_complementaires;
    }

    public function setInformations_complementaires(?string $informations_complementaires): self
    {
        $this->informations_complementaires = $informations_complementaires;
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

    public function getTypeAssistance(): ?string
    {
        return $this->type_assistance;
    }

    public function setTypeAssistance(?string $type_assistance): static
    {
        $this->type_assistance = $type_assistance;

        return $this;
    }

    public function getAeroportPort(): ?string
    {
        return $this->aeroport_port;
    }

    public function setAeroportPort(?string $aeroport_port): static
    {
        $this->aeroport_port = $aeroport_port;

        return $this;
    }

    public function getHeurePriseEnCharge(): ?\DateTimeInterface
    {
        return $this->heure_prise_en_charge;
    }

    public function setHeurePriseEnCharge(?\DateTimeInterface $heure_prise_en_charge): static
    {
        $this->heure_prise_en_charge = $heure_prise_en_charge;

        return $this;
    }

    public function getPointRendezVous(): ?string
    {
        return $this->point_rendez_vous;
    }

    public function setPointRendezVous(?string $point_rendez_vous): static
    {
        $this->point_rendez_vous = $point_rendez_vous;

        return $this;
    }

    public function getInformationsComplementaires(): ?string
    {
        return $this->informations_complementaires;
    }

    public function setInformationsComplementaires(?string $informations_complementaires): static
    {
        $this->informations_complementaires = $informations_complementaires;

        return $this;
    }

}
