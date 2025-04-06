<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\LivraisonRepository;

#[ORM\Entity(repositoryClass: LivraisonRepository::class)]
#[ORM\Table(name: 'livraisons')]
class Livraison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_livraisons = null;

    public function getId_livraisons(): ?int
    {
        return $this->id_livraisons;
    }

    public function setId_livraisons(int $id_livraisons): self
    {
        $this->id_livraisons = $id_livraisons;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $estimated_delivery = null;

    public function getEstimated_delivery(): ?\DateTimeInterface
    {
        return $this->estimated_delivery;
    }

    public function setEstimated_delivery(\DateTimeInterface $estimated_delivery): self
    {
        $this->estimated_delivery = $estimated_delivery;
        return $this;
    }

    #[ORM\Column(type: 'decimal', nullable: false)]
    private ?float $delivery_cost = null;

    public function getDelivery_cost(): ?float
    {
        return $this->delivery_cost;
    }

    public function setDelivery_cost(float $delivery_cost): self
    {
        $this->delivery_cost = $delivery_cost;
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

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $poids_colis = null;

    public function getPoids_colis(): ?int
    {
        return $this->poids_colis;
    }

    public function setPoids_colis(?int $poids_colis): self
    {
        $this->poids_colis = $poids_colis;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $destination_status = null;

    public function getDestination_status(): ?string
    {
        return $this->destination_status;
    }

    public function setDestination_status(?string $destination_status): self
    {
        $this->destination_status = $destination_status;
        return $this;
    }

    public function getIdLivraisons(): ?int
    {
        return $this->id_livraisons;
    }

    public function getEstimatedDelivery(): ?\DateTimeInterface
    {
        return $this->estimated_delivery;
    }

    public function setEstimatedDelivery(\DateTimeInterface $estimated_delivery): static
    {
        $this->estimated_delivery = $estimated_delivery;

        return $this;
    }

    public function getDeliveryCost(): ?string
    {
        return $this->delivery_cost;
    }

    public function setDeliveryCost(string $delivery_cost): static
    {
        $this->delivery_cost = $delivery_cost;

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

    public function getPoidsColis(): ?int
    {
        return $this->poids_colis;
    }

    public function setPoidsColis(?int $poids_colis): static
    {
        $this->poids_colis = $poids_colis;

        return $this;
    }

    public function getDestinationStatus(): ?string
    {
        return $this->destination_status;
    }

    public function setDestinationStatus(?string $destination_status): static
    {
        $this->destination_status = $destination_status;

        return $this;
    }

}
