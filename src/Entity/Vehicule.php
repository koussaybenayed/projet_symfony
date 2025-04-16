<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\VehiculeRepository;

#[ORM\Entity(repositoryClass: VehiculeRepository::class)]
#[ORM\Table(name: 'vehicules')]
class Vehicule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

   

    // Ajoutez d'autres propriétés ici en fonction de votre base de données

    public function getId(): ?int
    {
        return $this->id;
    }

    
    #[ORM\Column(name: "typeVehicule", type: "string", nullable: true)]
private ?string $typeVehicule = null;

    
    public function getTypeVehicule(): ?string
    {
        return $this->typeVehicule;
    }
    
    public function setTypeVehicule(?string $typeVehicule): self
    {
        $this->typeVehicule = $typeVehicule;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $CapacitéPassagers = null;

    public function getCapacitéPassagers(): ?int
    {
        return $this->CapacitéPassagers;
    }

    public function setCapacitéPassagers(?int $CapacitéPassagers): self
    {
        $this->CapacitéPassagers = $CapacitéPassagers;
        return $this;
    }

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $CapacitéPoids = null;

    public function getCapacitéPoids(): ?float
    {
        return $this->CapacitéPoids;
    }

    public function setCapacitéPoids(?float $CapacitéPoids): self
    {
        $this->CapacitéPoids = $CapacitéPoids;
        return $this;
    }

    #[ORM\Column(name: 'statutVehicule', type: 'string', nullable: true)]
    private ?string $statutVehicule = null;
    
    public function getStatutVehicule(): ?string
    {
        return $this->statutVehicule;
    }
    
    public function setStatutVehicule(?string $statutVehicule): self
    {
        $this->statutVehicule = $statutVehicule;
        return $this;
    }
  #[ORM\Column(name: 'consommationCarburant', type: 'float', nullable: true)]
private ?float $consommationCarburant = null;

public function getConsommationCarburant(): ?float
{
    return $this->consommationCarburant;
}

public function setConsommationCarburant(?float $consommationCarburant): self
{
    $this->consommationCarburant = $consommationCarburant;
    return $this;
}

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $nom = null;

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

}
