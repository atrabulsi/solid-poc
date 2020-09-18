<?php

namespace App\Core\DDD\Domain\Models;

use App\Core\DDD\Domain\Exceptions\StartDateMustBeBeforeEndDate;
use Carbon\Carbon;

class Wave
{
    private int $id;
    private string $name;
    private ?Carbon $startDate;
    private ?Carbon $endDate;
    private WaveStatus $status;

    /**
     * Wave constructor.
     * @param int|null $id
     * @param string $name
     * @param Carbon|null $startDate
     * @param Carbon|null $endDate
     * @param WaveStatus|null $status
     * @throws StartDateMustBeBeforeEndDate
     */
    public function __construct(
        ?int $id,
        string $name,
        ?Carbon $startDate,
        ?Carbon $endDate,
        ?WaveStatus $status
    ) {
        $this->id = $id ? $id : (int)hrtime(true);
        $this->name = $name;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status ? $status : WaveStatus::draft();
        $this->validate();
    }

    /**
     * @throws StartDateMustBeBeforeEndDate
     */
    private function validate()
    {
        if ($this->startDate && $this->endDate) {
            if ($this->startDate > $this->endDate) {
                throw new StartDateMustBeBeforeEndDate('Wave start date must be before end date');
            }
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStartDate(): ?Carbon
    {
        return $this->startDate;
    }

    public function getEndDate(): ?Carbon
    {
        return $this->endDate;
    }

    public function getStatus(): WaveStatus
    {
        return $this->status;
    }
}
