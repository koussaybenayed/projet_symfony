<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\ReductionRepository;

#[ORM\Entity(repositoryClass: ReductionRepository::class)]
#[ORM\Table(name: 'reductions')]
class Reduction
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
    private ?string $code = null;

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    #[ORM\Column(type: 'decimal', nullable: false)]
    private ?float $discount_amount = null;

    public function getDiscount_amount(): ?float
    {
        return $this->discount_amount;
    }

    public function setDiscount_amount(float $discount_amount): self
    {
        $this->discount_amount = $discount_amount;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $expiration_date = null;

    public function getExpiration_date(): ?string
    {
        return $this->expiration_date;
    }

    public function setExpiration_date(string $expiration_date): self
    {
        $this->expiration_date = $expiration_date;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $condition = null;

    public function getCondition(): ?string
    {
        return $this->condition;
    }

    public function setCondition(?string $condition): self
    {
        $this->condition = $condition;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $created_at = null;

    public function getCreated_at(): ?string
    {
        return $this->created_at;
    }

    public function setCreated_at(string $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: Billet::class, mappedBy: 'reduction')]
    private Collection $billets;

    public function __construct()
    {
        $this->billets = new ArrayCollection();
    }

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

    public function getDiscountAmount(): ?string
    {
        return $this->discount_amount;
    }

    public function setDiscountAmount(string $discount_amount): static
    {
        $this->discount_amount = $discount_amount;

        return $this;
    }

    public function getExpirationDate(): ?string
    {
        return $this->expiration_date;
    }

    public function setExpirationDate(string $expiration_date): static
    {
        $this->expiration_date = $expiration_date;

        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function setCreatedAt(string $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

}
