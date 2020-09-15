<?php

namespace App\Core\SingleResponsibility;

class WaveRepository
{
    public function getWaveById(int $id): ?Wave
    {
        if ($id === 1) {
            return new Wave(1, 'Best discount wave!');
        }
        return null;
    }
}
