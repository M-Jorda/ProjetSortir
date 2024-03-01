<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use App\Service\SortieStateService;
use App\Validator\Constraints\ValidVille;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SortieRepository::class)]
class Sortie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'Le nom de la sortie doit être indiqué ')]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message:'Merci d\'indiquer la date de la sortie ')]
    #[Assert\GreaterThanOrEqual(propertyPath: 'limiteDateInscription')]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $modifiedDate = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'Merci de préciser la durée de la sortie ')]
    #[Assert\Length(max: 255)]
    private ?string $duration = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message:'Merci de renseigner une date d\'inscription')]
    private ?\DateTimeInterface $limiteDateInscription = null;

    #[ORM\Column]
    #[Assert\Type(type:'integer', message: 'Le nombre de participants doit être un entier')]
    #[Assert\NotBlank(message: 'Merci de renseigner le nombre max de participants ')]
    private ?int $maxInscriptionsNumber = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message:'Merci de décrire votre sortie en quelques mots')]
    private ?string $infosSortie = null;


    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'sorties')]
    private Collection $participant;

    #[ORM\ManyToOne(inversedBy: 'sortiesOrganized')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $organisateur = null;

    #[ORM\ManyToOne(inversedBy: 'sortie')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Campus $campus = null;

    #[ORM\ManyToOne(inversedBy: 'sortie')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etat $etat  = null;

    #[ORM\ManyToOne(inversedBy: 'sortie')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Lieu $lieu = null;

    #[ORM\OneToOne(inversedBy:'sortie')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Archive $archive = null;


    public function __construct()
    {
        $this->participant = new ArrayCollection();
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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getModifiedDate(): ?\DateTimeInterface
    {
        return $this->modifiedDate;
    }

    public function setModifiedDate(?\DateTimeInterface $modifiedDate): static
    {
        $this->modifiedDate = $modifiedDate;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;

    }

    public function setDuration(string $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getLimiteDateInscription(): ?\DateTimeInterface
    {
        return $this->limiteDateInscription;
    }

    public function setLimiteDateInscription(\DateTimeInterface $limiteDateInscription): static
    {
        $this->limiteDateInscription = $limiteDateInscription;

        return $this;
    }

    public function getMaxInscriptionsNumber(): ?int
    {
        return $this->maxInscriptionsNumber;
    }

    public function setMaxInscriptionsNumber(int $maxInscriptionsNumber): static
    {
        $this->maxInscriptionsNumber = $maxInscriptionsNumber;

        return $this;
    }

    public function getInfosSortie(): ?string
    {
        return $this->infosSortie;
    }

    public function setInfosSortie(?string $infosSortie): static
    {
        $this->infosSortie = $infosSortie;

        return $this;
    }



    /**
     * @return Collection<int, User>
     */
    public function getParticipant(): Collection
    {
        return $this->participant;
    }

    public function addParticipant(User $participant): static
    {
        if (!$this->participant->contains($participant)) {
            $this->participant->add($participant);
        }

        return $this;
    }

    public function removeParticipant(User $participant): static
    {
        $this->participant->removeElement($participant);

        return $this;
    }

    public function getOrganisateur(): ?User
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?User $organisateur): static
    {
        $this->organisateur = $organisateur;

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

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }



    public function isFull(): bool
    {
        return count($this->participant) >= $this->maxInscriptionsNumber;
    }

    public function hasUserSubscribed(User $user): bool
    {
        foreach ($this->participant as $participant) {
            if ($participant === $user) {
                return true;
            }
        }

        return false;
    }

    public function updateEtat(SortieStateService $sortieStateService): void
    {
        $etat = $sortieStateService->getEtatObject($this);
        $this->etat = $etat;
    }


    public function getArchive(): ?Archive
    {
        return $this->archive;
    }

    public function setArchive(?Archive $archive): self
    {
        $this->archive = $archive;

        return $this;
    }







}
