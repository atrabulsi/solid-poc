<?php

namespace App\Core\SingleResponsibility\Repositories;

use App\Core\SingleResponsibility\Entities\Wave;

class WaveRepository
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
