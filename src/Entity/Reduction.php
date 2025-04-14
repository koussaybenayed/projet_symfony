<?php

namespace App\Entity;

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

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $code = null;

    #[ORM\Column(type: 'decimal', nullable: false)]
    private ?float $discount_amount = null;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $expiration_date = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $condition = null;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $created_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getDiscount_amount(): ?float
    {
        return $this->discount_amount;
    }

    public function setDiscount_amount(float $discount_amount): self
    {
        $this->discount_amount = $discount_amount;
        return $this;
    }

    public function getExpiration_date(): ?\DateTimeInterface
    {
        return $this->expiration_date;
    }

    public function setExpiration_date(\DateTimeInterface $expiration_date): self
    {
        $this->expiration_date = $expiration_date;
        return $this;
    }

    public function getCondition(): ?string
    {
        return $this->condition;
    }

    public function setCondition(?string $condition): self
    {
        $this->condition = $condition;
        return $this;
    }

    public function getCreated_at(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreated_at(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function __toString(): string
    {
        return $this->code ?? '';
    }
}