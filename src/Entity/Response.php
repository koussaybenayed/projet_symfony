<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\ResponseRepository;

#[ORM\Entity(repositoryClass: ResponseRepository::class)]
#[ORM\Table(name: 'responses')]
class Response
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

    #[ORM\Column(type: 'text', nullable: false)]
    private ?string $response_text = null;

    public function getResponse_text(): ?string
    {
        return $this->response_text;
    }

    public function setResponse_text(string $response_text): self
    {
        $this->response_text = $response_text;
        return $this;
    }

    #[ORM\Column(type: 'datetime', nullable: false)]
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

    #[ORM\Column(type: 'integer', nullable: false)]
    private ?int $reclamation_id = null;

    public function getReclamation_id(): ?int
    {
        return $this->reclamation_id;
    }

    public function setReclamation_id(int $reclamation_id): self
    {
        $this->reclamation_id = $reclamation_id;
        return $this;
    }

    public function getResponseText(): ?string
    {
        return $this->response_text;
    }

    public function setResponseText(string $response_text): static
    {
        $this->response_text = $response_text;

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

    public function getReclamationId(): ?int
    {
        return $this->reclamation_id;
    }

    public function setReclamationId(int $reclamation_id): static
    {
        $this->reclamation_id = $reclamation_id;

        return $this;
    }

}
