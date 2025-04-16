<?php
namespace App\Entity;
use App\Repository\ReductionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReductionRepository::class)]
#[ORM\Table(name: 'reductions')]
#[UniqueEntity(fields: ['code'], message: '🚫 Ce code est déjà utilisé. Veuillez en choisir un autre.')]
class Reduction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank(message: "Le code ne doit pas être vide.")]
    #[Assert\Type('string', message: "Le code doit être une chaîne de caractères.")]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9]*$/',
        message: "Le code doit contenir uniquement des caractères alphanumériques."
    )]
    private ?string $code = null;

    #[ORM\Column(name: 'discount_amount', type: 'decimal', precision: 10, scale: 2)]
    #[Assert\NotBlank(message: "Le montant de la réduction ne doit pas être vide.")]
    #[Assert\Type(
        type: 'numeric',
        message: "Veuillez entrer un nombre."
    )]
    private ?float $discountAmount = null;

    #[ORM\Column(name: 'expiration_date', type: 'datetime')]
    #[Assert\NotBlank(message: "La date d'expiration ne doit pas être vide.")]
    #[Assert\Type(\DateTimeInterface::class, message: "La date d'expiration doit être une date valide.")]
    private ?\DateTimeInterface $expirationDate = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Type('string', message: "Le texte de condition doit être une chaîne de caractères.")]
    #[Assert\NotBlank(message: "Le texte de condition ne doit pas être vide.")]
    #[Assert\Regex(
        pattern: '/^[\p{L}\s.,;:\'"\-?!()éèêàçùôîïüë]*$/u',
        message: "Le texte de condition ne peut contenir que des lettres, des espaces et de la ponctuation."
    )]
   
    private ?string $conditionText = null;

    #[ORM\Column(name: 'created_at', type: 'datetime')]
    #[Assert\NotBlank(message: "La date de création ne doit pas être vide.")]
    #[Assert\Type(\DateTimeInterface::class, message: "La date de création doit être une date valide.")]
    #[Assert\Expression(
        "this.getCreatedAt() < this.getExpirationDate()",
        message: "La date de création doit être antérieure à la date d'expiration."
    )]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\OneToMany(targetEntity: Billet::class, mappedBy: 'reduction')]
    private Collection $billets;

    public function __construct()
    {
        $this->billets = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
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

    public function getExpirationDate(): ?\DateTimeInterface
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(?\DateTimeInterface $expirationDate): self
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return Collection<int, Billet>
     */
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


