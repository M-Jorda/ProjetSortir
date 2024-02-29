<?php

namespace App\Entity;

use App\Repository\ArchiveRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArchiveRepository::class)]
class Archive
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $cancelled = null;

    #[ORM\Column]
    private ?bool $passed = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $motif_delete = null;

    #[ORM\ManyToOne(targetEntity: Etat::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etat $etat = null;

    #[ORM\OneToOne(targetEntity: Sortie::class, mappedBy: 'Archive')]
    private Collection $sorties;

    public function __construct()
    {
        $this->sorties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isCancelled(): ?bool
    {
        return $this->cancelled;
    }

    public function setCancelled(bool $cancelled): static
    {
        $this->cancelled = $cancelled;

        return $this;
    }

    public function isPassed(): ?bool
    {
        return $this->passed;
    }

    public function setPassed(bool $passed): static
    {
        $this->passed = $passed;

        return $this;
    }

    public function getMotifDelete(): ?string
    {
        return $this->motif_delete;
    }

    public function setMotifDelete(?string $motif_delete): static
    {
        $this->motif_delete = $motif_delete;

        return $this;
    }
    public function getEtat():?Etat
    {
        return $this->etat;
    }
    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
    /**
     * @return Collection<int, Sortie>
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSortie(Sortie $sortie): self
    {
        if (!$this->sorties->contains($sortie)) {
            $this->sorties[] = $sortie;
            $sortie->setArchive($this);
        }

        return $this;
    }

    public function removeSortie(Sortie $sortie): self
    {
        if ($this->sorties->removeElement($sortie)) {
            // Définissez le côté inverse à null (sauf si déjà modifié)
            if ($sortie->getArchive() === $this) {
                $sortie->setArchive(null);
            }
        }

        return $this;
    }
}

