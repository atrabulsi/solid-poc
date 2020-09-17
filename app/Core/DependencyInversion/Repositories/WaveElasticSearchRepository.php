<?php

namespace App\Core\DependencyInversion\Repositories;

use App\Core\DependencyInversion\Entities\Wave;
use App\Core\DependencyInversion\Interfaces\WaveRepositoryInterface;

class WaveElasticSearchRepository implements WaveRepositoryInterface
{
    public function getWaveById(int $id): ?Wave
    {
        if ($id === 1) {
            return new Wave(1, 'Best discount wave!');
        } elseif ($id === 2) {
            return new Wave(2, 'Another great wave!');
        }
        return null;
    }
}
