<?php

namespace App\Core\DependencyInversion\Repositories;

use App\Core\DependencyInversion\Entities\Wave;
use App\Core\DependencyInversion\Interfaces\WaveRepositoryInterface;

class WaveRepository implements WaveRepositoryInterface
{
    public function getWaveById(int $id): ?Wave
    {
        sleep(2);
        if ($id === 1) {
            return new Wave(1, 'Best discount wave!');
        }
        return null;
    }
}
