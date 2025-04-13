<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
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
    #[Assert\NotBlank(message: "La date de livraison estimée est obligatoire")]
    #[Assert\GreaterThan("today", message: "La date de livraison estimée doit être dans le futur")]
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
    #[Assert\NotBlank(message: "Le coût de livraison est obligatoire")]
    #[Assert\Positive(message: "Le coût de livraison doit être positif")]
    #[Assert\Type(type: "numeric", message: "Le coût de livraison doit être un nombre")]
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
    #[Assert\NotBlank(message: "La date de création est obligatoire")]
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
    #[Assert\Type(type: "integer", message: "Le poids du colis doit être un nombre entier")]
    #[Assert\PositiveOrZero(message: "Le poids du colis ne peut pas être négatif")]
    #[Assert\LessThanOrEqual(
        value: 20,
        message: "Le poids du colis ne doit pas dépasser 20 kg"
    )]
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
    #[Assert\Length(
        max: 255,
        maxMessage: "Le statut de destination ne peut pas dépasser {{ limit }} caractères"
    )]
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

    #[Assert\Callback]
    public function validateEstimatedDeliveryAfterCreation(ExecutionContextInterface $context): void
    {
        if ($this->estimated_delivery !== null && $this->created_at !== null) {
            if ($this->estimated_delivery <= $this->created_at) {
                $context->buildViolation('La date de livraison estimée doit être ultérieure à la date de création')
                    ->atPath('estimated_delivery')
                    ->addViolation();
            }
        }
    }
}