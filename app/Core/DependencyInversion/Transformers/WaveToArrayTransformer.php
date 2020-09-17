<?php

namespace App\Core\DependencyInversion\Transformers;


use App\Core\DependencyInversion\Entities\Wave;
use App\Core\DependencyInversion\Interfaces\WaveTransformerInterface;

class WaveToArrayTransformer implements WaveTransformerInterface
{
    private array $data = [];

    public function write(Wave $wave)
    {
        $this->data = [
            'id' => $wave->getId(),
            'title' => $wave->getTitle(),
        ];
    }

    public function read(): array
    {
        return $this->data;
    }
}
