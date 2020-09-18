<?php

namespace App\Core\DDD\Application\GetWave;

class GetWaveQuery
{
    private int $id;
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
