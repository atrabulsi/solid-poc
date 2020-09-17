<?php

namespace App\Core\DependencyInversion\Interfaces;

use App\Core\DependencyInversion\Entities\Wave;

interface WaveRepositoryInterface
{
    public function getWaveById(int $id): ?Wave;
}
