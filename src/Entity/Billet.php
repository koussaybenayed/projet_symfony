<?php

namespace App\Entity;

use App\Repository\BilletRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: BilletRepository::class)]
#[ORM\Table(name: 'billets')]
class Billet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $dateDepart = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $dateArrive = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $compagnie = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $depart = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $destination = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $prix = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $typeTransport = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $classe = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $statut = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "user_id")] 
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Reduction::class)]
    private ?Reduction $reduction = null;



    #[ORM\OneToOne(targetEntity: Assistance::class, mappedBy: 'billet')]
    private ?Assistance $assistance = null;

   
   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->dateDepart;
    }

    public function setDateDepart(?\DateTimeInterface $dateDepart): self
    {
        $this->dateDepart = $dateDepart;
        return $this;
    }

    public function getDateArrive(): ?\DateTimeInterface
    {
        return $this->dateArrive;
    }

    public function setDateArrive(?\DateTimeInterface $dateArrive): self
    {
        $this->dateArrive = $dateArrive;
        return $this;
    }

    public function getCompagnie(): ?string
    {
        return $this->compagnie;
    }

    public function setCompagnie(?string $compagnie): self
    {
        $this->compagnie = $compagnie;
        return $this;
    }

    public function getDepart(): ?string
    {
        return $this->depart;
    }

    public function setDepart(?string $depart): self
    {
        $this->depart = $depart;
        return $this;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(?string $destination): self
    {
        $this->destination = $destination;
        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;
        return $this;
    }

    public function getTypeTransport(): ?string
    {
        return $this->typeTransport;
    }

    public function setTypeTransport(?string $typeTransport): self
    {
        $this->typeTransport = $typeTransport;
        return $this;
    }

    public function getClasse(): ?string
    {
        return $this->classe;
    }

    public function setClasse(?string $classe): self
    {
        $this->classe = $classe;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getReduction(): ?Reduction
    {
        return $this->reduction;
    }

    public function setReduction(?Reduction $reduction): self
    {
        $this->reduction = $reduction;
        return $this;
    }

       public function getAssistance(): ?Assistance
    {
        return $this->assistance;
    }

    public function setAssistance(?Assistance $assistance): self
    {
        $this->assistance = $assistance;
        return $this;
    }
    #[Assert\Callback]
    public function validateDates(ExecutionContextInterface $context): void
    {
        if ($this->dateDepart && $this->dateArrive && $this->dateDepart > $this->dateArrive) {
            $context->buildViolation('La date de départ doit être antérieure ou égale à la date d\'arrivée.')
                ->atPath('dateDepart')
                ->addViolation();
        }
    }
}
