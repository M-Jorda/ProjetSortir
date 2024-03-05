<?php

namespace App\Entity;

use App\Repository\PieceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PieceRepository::class)]
class Piece
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private bool $piece1 = false;

    #[ORM\Column]
    private bool $piece2 = false;

    #[ORM\Column]
    private bool $piece3 = false;

    #[ORM\Column]
    private bool $piece4 = false;

    #[ORM\Column]
    private bool $piece5 = false;

    #[ORM\OneToMany(targetEntity: User::class, mappedBy: "piece")]
    private Collection $point;


    public function __construct()
    {
        $this->point = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isPiece1(): ?bool
    {
        return $this->piece1;
    }

    public function setPiece1(bool $piece1): static
    {
        $this->piece1 = $piece1;

        return $this;
    }

    public function isPiece2(): ?bool
    {
        return $this->piece2;
    }

    public function setPiece2(bool $piece2): static
    {
        $this->piece2 = $piece2;

        return $this;
    }

    public function isPiece3(): ?bool
    {
        return $this->piece3;
    }

    public function setPiece3(bool $piece3): static
    {
        $this->piece3 = $piece3;

        return $this;
    }

    public function isPiece4(): ?bool
    {
        return $this->piece4;
    }

    public function setPiece4(bool $piece4): static
    {
        $this->piece4 = $piece4;

        return $this;
    }

    public function isPiece5(): ?bool
    {
        return $this->piece5;
    }

    public function setPiece5(bool $piece5): static
    {
        $this->piece5 = $piece5;

        return $this;
    }

    /**
     * @return Collection<int, user>
     */
    public function getPoint(): Collection
    {
        return $this->point;
    }

    public function addPoint(user $point): static
    {
        if (!$this->point->contains($point)) {
            $this->point->add($point);
            $point->setPiece($this);
        }

        return $this;
    }

    public function removePoint(user $point): static
    {
        if ($this->point->removeElement($point)) {
            // set the owning side to null (unless already changed)
            if ($point->getPiece() === $this) {
                $point->setPiece(null);
            }
        }

        return $this;
    }
}
