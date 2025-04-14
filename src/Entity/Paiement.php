<?php

namespace App\Entity;

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

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'paiements')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private ?User $user = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Billet::class, inversedBy: 'paiements')]
    #[ORM\JoinColumn(name: 'billet_id', referencedColumnName: 'id')]
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

    #[ORM\ManyToOne(targetEntity: Coli::class, inversedBy: 'paiements')]
    #[ORM\JoinColumn(name: 'colis_id', referencedColumnName: 'id')]
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

}
