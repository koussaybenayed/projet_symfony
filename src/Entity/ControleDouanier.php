<?php
namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ControleDouanierRepository;

#[ORM\Entity(repositoryClass: ControleDouanierRepository::class)]
#[ORM\Table(name: 'controle_douanier')]
class ControleDouanier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_controle = null;

    #[ORM\ManyToOne(targetEntity: Livraison::class)]
    #[ORM\JoinColumn(name: 'id_livraisons', referencedColumnName: 'id_livraisons', nullable: false)]
    #[Assert\NotNull(message: "La livraison est obligatoire")]
    private ?Livraison $livraison = null;

    #[ORM\Column(type: 'string', length: 100, nullable: false)]
    #[Assert\NotBlank(message: "Le pays de douane est obligatoire")]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: "Le pays de douane doit contenir au moins {{ limit }} caractères",
        maxMessage: "Le pays de douane ne peut pas dépasser {{ limit }} caractères"
    )]
    private ?string $pays_douane = null;

    #[ORM\Column(type: 'string', length: 20, nullable: false)]
    #[Assert\NotBlank(message: "Le statut est obligatoire")]
    #[Assert\Choice(
        choices: ["En attente", "En cours", "Validé", "Rejeté"],
        message: "Choisissez un statut valide: En attente, En cours, Validé ou Rejeté"
    )]
    private ?string $statut = null;

    #[ORM\Column(type: 'date', nullable: false)]
    #[Assert\NotBlank(message: "La date de contrôle est obligatoire")]
    #[Assert\Type("\DateTimeInterface", message: "La date n'est pas valide")]
    private ?\DateTimeInterface $date_controle = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\Length(
        max: 1000,
        maxMessage: "Les commentaires ne peuvent pas dépasser {{ limit }} caractères"
    )]
    private ?string $commentaires = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $latitude = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $longitude = null;

    // Getters and Setters

    public function getIdControle(): ?int
    {
        return $this->id_controle;
    }

    public function getLivraison(): ?Livraison
    {
        return $this->livraison;
    }

    public function setLivraison(?Livraison $livraison): static
    {
        $this->livraison = $livraison;
        return $this;
    }

    public function getPaysDouane(): ?string
    {
        return $this->pays_douane;
    }

    public function setPaysDouane(string $pays_douane): static
    {
        $this->pays_douane = $pays_douane;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;
        return $this;
    }

    public function getDateControle(): ?\DateTimeInterface
    {
        return $this->date_controle;
    }

    public function setDateControle(\DateTimeInterface $date_controle): static
    {
        $this->date_controle = $date_controle;
        return $this;
    }

    public function getCommentaires(): ?string
    {
        return $this->commentaires;
    }

    public function setCommentaires(?string $commentaires): static
    {
        $this->commentaires = $commentaires;
        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): static
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): static
    {
        $this->longitude = $longitude;
        return $this;
    }
}