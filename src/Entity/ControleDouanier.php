<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ControleDouanierRepository;

#[ORM\Entity(repositoryClass: ControleDouanierRepository::class)]
#[ORM\Table(name: 'controle_douanier')]
class ControleDouanier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_controle = null;

    public function getIdControle(): ?int
    {
        return $this->id_controle;
    }

    public function setIdControle(int $idControle): self
    {
        $this->id_controle = $idControle;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Livraison::class, inversedBy: 'controleDouaniers')]
    #[ORM\JoinColumn(name: 'id_livraisons', referencedColumnName: 'id_livraisons')]
    private ?Livraison $livraison = null;

    public function getLivraison(): ?Livraison
    {
        return $this->livraison;
    }

    public function setLivraison(?Livraison $livraison): self
    {
        $this->livraison = $livraison;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $pays_douane = null;

    public function getPaysDouane(): ?string
    {
        return $this->pays_douane;
    }

    public function setPaysDouane(string $paysDouane): self
    {
        $this->pays_douane = $paysDouane;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $statut = null;

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    #[ORM\Column(type: 'date', nullable: false)]
    private ?\DateTimeInterface $date_controle = null;

    public function getDateControle(): ?\DateTimeInterface
    {
        return $this->date_controle;
    }

    public function setDateControle(\DateTimeInterface $dateControle): self
    {
        $this->date_controle = $dateControle;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $commentaires = null;

    public function getCommentaires(): ?string
    {
        return $this->commentaires;
    }

    public function setCommentaires(?string $commentaires): self
    {
        $this->commentaires = $commentaires;
        return $this;
    }
}
