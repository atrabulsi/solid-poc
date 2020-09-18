<?php

namespace App\Core\DDD\Application\CreateWave;

use Carbon\Carbon;

class CreateWaveCommand
{
    private string $waveName;
    private ?Carbon $startDate;
    private ?Carbon $endDate;

    public function __construct(
        string $waveName,
        ?Carbon $startDate,
        ?Carbon $endDate
    ) {
        $this->waveName = $waveName;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function getWaveName(): string
    {
        return $this->waveName;
    }

    public function getStartDate(): ?Carbon
    {
        return $this->startDate;
    }

    public function getEndDate(): ?Carbon
    {
        return $this->endDate;
    }
}
