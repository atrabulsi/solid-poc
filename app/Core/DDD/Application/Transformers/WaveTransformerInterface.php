<?php

namespace App\Core\DDD\Application\Transformers;

use App\Core\DDD\Domain\Models\Wave;

interface WaveTransformerInterface
{
    public function write(Wave $wave);
    public function read();
}
