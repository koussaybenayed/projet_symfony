<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\ReductionRepository;
use DateTimeInterface;

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

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: false)]
    private ?float $discountAmount = null;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?DateTimeInterface $expirationDate = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $conditionText = null;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?DateTimeInterface $createdAt = null;

    #[ORM\OneToMany(targetEntity: Billet::class, mappedBy: 'reduction')]
    private Collection $billets;

    public function __construct()
    {
        $this->billets = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable(); // Auto-set creation date
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDiscountAmount(): ?float
    {
        return $this->discountAmount;
    }

    public function setDiscountAmount(float $discountAmount): self
    {
        $this->discountAmount = $discountAmount;
        return $this;
    }

    public function getExpirationDate(): ?DateTimeInterface
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(DateTimeInterface $expirationDate): self
    {
        $this->expirationDate = $expirationDate;
        return $this;
    }

    public function getConditionText(): ?string
    {
        return $this->conditionText;
    }

    public function setConditionText(?string $conditionText): self
    {
        $this->conditionText = $conditionText;
        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getBillets(): Collection
    {
        return $this->billets;
    }

    public function addBillet(Billet $billet): self
    {
        if (!$this->billets->contains($billet)) {
            $this->billets->add($billet);
            $billet->setReduction($this);
        }
        return $this;
    }

    public function removeBillet(Billet $billet): self
    {
        if ($this->billets->removeElement($billet)) {
            // set the owning side to null (unless already changed)
            if ($billet->getReduction() === $this) {
                $billet->setReduction(null);
            }
        }
        return $this;
    }
}