<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity(fields: ['name'], message: 'Un groupe avec ce nom existe déjà')]

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: 'Merci de renseigner un nom pour le groupe')]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'idGroup')]
    private Collection $users;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createdDate = null;

    #[ORM\ManyToMany(targetEntity: Sortie::class, inversedBy: 'groupes')]
    private Collection $idGroup;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->idGroup = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setIdGroup($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getIdGroup() === $this) {
                $user->setIdGroup(null);
            }
        }

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): static
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getIdGroup(): Collection
    {
        return $this->idGroup;
    }

    public function addIdGroup(Sortie $idGroup): static
    {
        if (!$this->idGroup->contains($idGroup)) {
            $this->idGroup->add($idGroup);
        }

        return $this;
    }

    public function removeIdGroup(Sortie $idGroup): static
    {
        $this->idGroup->removeElement($idGroup);

        return $this;
    }
}
