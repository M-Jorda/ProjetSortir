<?php

namespace App\DTO;

use App\Entity\Campus;
use DateTime;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class SortieDTO
{
    private string $name = '';
    private ?\DateTimeInterface $filterDate = null;
    private ?\DateTimeInterface $filterDateMax = null;
    private bool $sortiePasse = false;
    private bool $checkboxOrga = false;
    private bool $checkBoxInscrit = false;
    private bool $checkBoxNotInscrit = false;
    private ?Campus $campus = null;

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): void
    {
        $this->campus = $campus;
    }

    public function isSortiePasse(): bool
    {
        return $this->sortiePasse;
    }

    public function setSortiePasse(bool $sortiePasse): void
    {
        $this->sortiePasse = $sortiePasse;
    }




    public function isCheckBoxNotInscrit(): bool
    {
        return $this->checkBoxNotInscrit;
    }

    public function setCheckBoxNotInscrit(bool $checkBoxNotInscrit): void
    {
        $this->checkBoxNotInscrit = $checkBoxNotInscrit;
    }

    public function isCheckBoxInscrit(): bool
    {
        return $this->checkBoxInscrit;
    }

    public function setCheckBoxInscrit(bool $checkBoxInscrit): void
    {
        $this->checkBoxInscrit = $checkBoxInscrit;
    }



    public function getCheckboxOrga(): bool
    {
        return $this->checkboxOrga;
    }

    public function setCheckboxOrga(bool $checkboxOrga): void
    {
        $this->checkboxOrga = $checkboxOrga;
    }



    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getFilterDate(): ?\DateTimeInterface
    {
        return $this->filterDate;
    }

    public function setFilterDate(?\DateTimeInterface $filterDate): void
    {
        $this->filterDate = $filterDate;
    }

    public function getFilterDateMax(): ?\DateTimeInterface
    {
        return $this->filterDateMax;
    }

    public function setFilterDateMax(?\DateTimeInterface $filterDateMax): void
    {
        $this->filterDateMax = $filterDateMax;
    }


}