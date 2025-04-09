<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\PaiementRepository;

#[ORM\Entity(repositoryClass: PaiementRepository::class)]
#[ORM\Table(name: 'paiements')]
class Paiement
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
    private ?string $payment_method = null;

    public function getPayment_method(): ?string
    {
        return $this->payment_method;
    }

    public function setPayment_method(string $payment_method): self
    {
        $this->payment_method = $payment_method;
        return $this;
    }

    #[ORM\Column(type: 'decimal', nullable: false)]
    private ?float $amount = null;

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $payment_date = null;

    public function getPayment_date(): ?\DateTimeInterface
    {
        return $this->payment_date;
    }

    public function setPayment_date(\DateTimeInterface $payment_date): self
    {
        $this->payment_date = $payment_date;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $status = null;

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
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

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $billet_id = null;

    public function getBillet_id(): ?int
    {
        return $this->billet_id;
    }

    public function setBillet_id(?int $billet_id): self
    {
        $this->billet_id = $billet_id;
        return $this;
    }

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $colis_id = null;

    public function getColis_id(): ?int
    {
        return $this->colis_id;
    }

    public function setColis_id(?int $colis_id): self
    {
        $this->colis_id = $colis_id;
        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->payment_method;
    }

    public function setPaymentMethod(string $payment_method): static
    {
        $this->payment_method = $payment_method;

        return $this;
    }

    public function getPaymentDate(): ?\DateTimeInterface
    {
        return $this->payment_date;
    }

    public function setPaymentDate(\DateTimeInterface $payment_date): static
    {
        $this->payment_date = $payment_date;

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

    public function getBilletId(): ?int
    {
        return $this->billet_id;
    }

    public function setBilletId(?int $billet_id): static
    {
        $this->billet_id = $billet_id;

        return $this;
    }

    public function getColisId(): ?int
    {
        return $this->colis_id;
    }

    public function setColisId(?int $colis_id): static
    {
        $this->colis_id = $colis_id;

        return $this;
    }

}
