<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Repository\RoleRepository;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
#[ORM\Table(name: 'role')]
class Role
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $role_id = null;

    public function getRole_id(): ?int
    {
        return $this->role_id;
    }

    public function setRole_id(int $role_id): self
    {
        $this->role_id = $role_id;
        return $this;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $role_name = null;

    public function getRole_name(): ?string
    {
        return $this->role_name;
    }

    public function setRole_name(?string $role_name): self
    {
        $this->role_name = $role_name;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'role')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        if (!$this->users instanceof Collection) {
            $this->users = new ArrayCollection();
        }
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->getUsers()->contains($user)) {
            $this->getUsers()->add($user);
        }
        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->getUsers()->removeElement($user);
        return $this;
    }

    public function getRoleId(): ?int
    {
        return $this->role_id;
    }

    public function getRoleName(): ?string
    {
        return $this->role_name;
    }

    public function setRoleName(?string $role_name): static
    {
        $this->role_name = $role_name;

        return $this;
    }

}
