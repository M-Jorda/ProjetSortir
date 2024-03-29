<?php

namespace App\Entity;

use AllowDynamicProperties;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[AllowDynamicProperties] #[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'Un compte avec cet email existe déjà')]
#[UniqueEntity(fields: ['pseudo'], message: 'Un compte avec ce pseudo existe déjà')]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Merci de renseigner un e-mail')]
    #[Assert\Email(message: 'L\'adresse e-mail "{{ value }}" n\'est pas valide.')]
    private ?string $email;

    /*
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Merci de renseigner un nom de famille')]
    private ?string $lastname = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Merci de renseigner un prénom')]
    private ?string $FirstName = null;

    #[ORM\Column(length: 15)]
    #[Assert\NotBlank(message: 'Merci de renseigner un numéro de téléphone')]
    #[Assert\Regex(
        pattern: "/^\+(?:[0-9] ?){6,14}[0-9]$/",
        message: 'Le numéro de téléphone "{{ value }}" n\'est pas valide.')]
    private ?string $PhoneNumber = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Merci de renseigner ce champ', groups: ['regAdmin'])]
    private ?bool $Blocked = null;

    #[ORM\ManyToMany(targetEntity: Sortie::class, mappedBy: 'participant', cascade: ["remove"])]
    private Collection $sorties;

    #[ORM\OneToMany(targetEntity: Sortie::class, mappedBy: 'organisateur', cascade: ["remove"])]
    private Collection $sortiesOrganized;

    #[ORM\ManyToOne(inversedBy: 'user')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Campus $campus = null;


    #[ORM\Column(length: 50, unique: true)]
    private ?string $pseudo = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    #[ORM\ManyToOne(targetEntity: Piece::class, inversedBy: "point", cascade: ["persist"])]
    private ?Piece $piece = null;

    #[ORM\ManyToMany(targetEntity: Group::class, mappedBy: 'users')]
    private Collection $groupes;

    //   #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    //   private ?\DateTimeImmutable $updatedAt = null;


    public function __construct()
    {
        $this->sorties = new ArrayCollection();
        $this->sortiesOrganized = new ArrayCollection();
        $this->groupes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @return list<string>
     * @see UserInterface
     *
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;


        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->FirstName;
    }

    public function setFirstName(string $FirstName): static
    {
        $this->FirstName = $FirstName;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->PhoneNumber;
    }

    public function setPhoneNumber(string $PhoneNumber): static
    {
        $this->PhoneNumber = $PhoneNumber;

        return $this;
    }

    public function isBlocked(): ?bool
    {
        return $this->Blocked;
    }

    public function setBlocked(bool $Blocked): static
    {
        $this->Blocked = $Blocked;

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSorty(Sortie $sorty): static
    {
        if (!$this->sorties->contains($sorty)) {
            $this->sorties->add($sorty);
            $sorty->addParticipant($this);
        }

        return $this;
    }

    public function removeSorty(Sortie $sorty): static
    {
        if ($this->sorties->removeElement($sorty)) {
            $sorty->removeParticipant($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSortiesOrganized(): Collection
    {
        return $this->sortiesOrganized;
    }

    public function addSortiesOrganized(Sortie $sortiesOrganized): static
    {
        if (!$this->sortiesOrganized->contains($sortiesOrganized)) {
            $this->sortiesOrganized->add($sortiesOrganized);
            $sortiesOrganized->setOrganisateur($this);
        }

        return $this;
    }

    public function removeSortiesOrganized(Sortie $sortiesOrganized): static
    {
        if ($this->sortiesOrganized->removeElement($sortiesOrganized)) {
            // set the owning side to null (unless already changed)
            if ($sortiesOrganized->getOrganisateur() === $this) {
                $sortiesOrganized->setOrganisateur(null);
            }
        }

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): static
    {
        $this->campus = $campus;

        return $this;
    }


    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo)
    {
        $this->pseudo = $pseudo;

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

    public function getUpdatedDate(): ?\DateTimeInterface
    {
        return $this->updatedDate;
    }

    public function setUpdatedDate(?\DateTimeInterface $updatedDate): static
    {
        $this->updatedDate = $updatedDate;

        return $this;
    }

    public function setUpdatedAtValue(): static
    {
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }


    public function getPiece(): ?Piece
    {
        return $this->piece;
    }

    public function setPiece(?Piece $piece): static
    {
        $this->piece = $piece;

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Group $groupe): static
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes->add($groupe);
            $groupe->addUser($this);
        }

        return $this;
    }

    public function removeGroupe(Group $groupe): static
    {
        if ($this->groupes->removeElement($groupe)) {
            $groupe->removeUser($this);
        }

        return $this;
    }

}