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
    private ?int $ID = null;

    public function getID(): ?int
    {
        return $this->ID;
    }

    public function setID(int $ID): self
    {
        $this->ID = $ID;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Billet::class, inversedBy: 'assistances')]
    #[ORM\JoinColumn(name: 'Billet_ID', referencedColumnName: 'id')]
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

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $Type_assistance = null;

    public function getType_assistance(): ?string
    {
        return $this->Type_assistance;
    }

    public function setType_assistance(string $Type_assistance): self
    {
        $this->Type_assistance = $Type_assistance;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $Aeroport_Port = null;

    public function getAeroport_Port(): ?string
    {
        return $this->Aeroport_Port;
    }

    public function setAeroport_Port(string $Aeroport_Port): self
    {
        $this->Aeroport_Port = $Aeroport_Port;
        return $this;
    }

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $Heure_prise_en_charge = null;

    public function getHeure_prise_en_charge(): ?\DateTimeInterface
    {
        return $this->Heure_prise_en_charge;
    }

    public function setHeure_prise_en_charge(\DateTimeInterface $Heure_prise_en_charge): self
    {
        $this->Heure_prise_en_charge = $Heure_prise_en_charge;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $Point_rendez_vous = null;

    public function getPoint_rendez_vous(): ?string
    {
        return $this->Point_rendez_vous;
    }

    public function setPoint_rendez_vous(string $Point_rendez_vous): self
    {
        $this->Point_rendez_vous = $Point_rendez_vous;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $Informations_complementaires = null;

    public function getInformations_complementaires(): ?string
    {
        return $this->Informations_complementaires;
    }

    public function setInformations_complementaires(?string $Informations_complementaires): self
    {
        $this->Informations_complementaires = $Informations_complementaires;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $Statut = null;

    public function getStatut(): ?string
    {
        return $this->Statut;
    }

    public function setStatut(string $Statut): self
    {
        $this->Statut = $Statut;
        return $this;
    }

    public function getTypeAssistance(): ?string
    {
        return $this->Type_assistance;
    }

    public function setTypeAssistance(string $Type_assistance): static
    {
        $this->Type_assistance = $Type_assistance;

        return $this;
    }

    public function getAeroportPort(): ?string
    {
        return $this->Aeroport_Port;
    }

    public function setAeroportPort(string $Aeroport_Port): static
    {
        $this->Aeroport_Port = $Aeroport_Port;

        return $this;
    }

    public function getHeurePriseEnCharge(): ?\DateTimeInterface
    {
        return $this->Heure_prise_en_charge;
    }

    public function setHeurePriseEnCharge(\DateTimeInterface $Heure_prise_en_charge): static
    {
        $this->Heure_prise_en_charge = $Heure_prise_en_charge;

        return $this;
    }

    public function getPointRendezVous(): ?string
    {
        return $this->Point_rendez_vous;
    }

    public function setPointRendezVous(string $Point_rendez_vous): static
    {
        $this->Point_rendez_vous = $Point_rendez_vous;

        return $this;
    }

    public function getInformationsComplementaires(): ?string
    {
        return $this->Informations_complementaires;
    }

    public function setInformationsComplementaires(?string $Informations_complementaires): static
    {
        $this->Informations_complementaires = $Informations_complementaires;

        return $this;
    }

}
