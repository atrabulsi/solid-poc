<?php

namespace App\Core\DependencyInversion\Interfaces;

use App\Core\DependencyInversion\Entities\Wave;

interface WaveTransformerInterface
{
    public function write(Wave $wave);

    public function read();
}
