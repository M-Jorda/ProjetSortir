<?php

namespace App\DTO;

use DateTime;

class SortieDTO
{
    private ?string $name = null ;
    private ?DateTime $filterDate = null;
    private ?DateTime $filterDateMax = null;

    public function __construct(?string $name = null, ?DateTime $filterDate = null, ?DateTime $filterDateMax = null)
    {
        $this->name = $name;
        $this->filterDate = $filterDate;
        $this->filterDateMax = $filterDateMax;
    }

    public function getName(): string
    {
        return $this->name ?? '';
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getFilterDate(): ?DateTime
    {
        return $this->filterDate;
    }

    public function setFilterDate(?DateTime $filterDate): void
    {
        $this->filterDate = $filterDate;
    }

    public function getFilterDateMax(): ?DateTime
    {
        return $this->filterDateMax;
    }

    public function setFilterDateMax(?DateTime $filterDateMax): void
    {
        $this->filterDateMax = $filterDateMax;
    }


}