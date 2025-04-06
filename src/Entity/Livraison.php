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

    #[ORM\OneToOne(targetEntity: Coli::class, inversedBy: 'livraison')]
    #[ORM\JoinColumn(name: 'colis_id', referencedColumnName: 'id', unique: true)]
    private ?Coli $coli = null;

    public function getColi(): ?Coli
    {
        return $this->coli;
    }

    public function setColi(?Coli $coli): self
    {
        $this->coli = $coli;
        return $this;
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

}
