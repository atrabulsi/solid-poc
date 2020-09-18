<?php

namespace App\Core\DDD\Application\Transformers;

use App\Core\DDD\Domain\Models\Wave;

class WaveToArrayTransformer implements WaveTransformerInterface
{
    private array $data = [];

    public function write(Wave $wave)
    {
        $this->data = [
            'id' => $wave->getId(),
            'name' => $wave->getName(),
            'startDate' => $wave->getStartDate() ? $wave->getStartDate()->toDateTimeString(): null,
            'endDate' => $wave->getEndDate() ? $wave->getEndDate()->toDateTimeString(): null,
            'status' => $wave->getStatus()->getStatus()
        ];
    }

    public function read(): array
    {
        return $this->data;
    }
}
