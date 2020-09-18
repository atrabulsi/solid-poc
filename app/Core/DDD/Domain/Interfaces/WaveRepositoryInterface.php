<?php

namespace App\Core\DDD\Domain\Interfaces;

use App\Core\DDD\Domain\Models\Wave;
use App\Core\DDD\Infrastructure\Exceptions\InfrastructureException;

interface WaveRepositoryInterface
{
    /**
     * @param int $id
     * @return Wave|null
     * @throws InfrastructureException
     */
    public function getWave(int $id): ?Wave;

    /**
     * @param Wave $wave
     * @throws InfrastructureException
     */
    public function createWave(Wave $wave);
}
