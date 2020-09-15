<?php

namespace App\Core\SingleResponsibility\Transformers;


use App\Core\SingleResponsibility\Entities\Wave;

class WaveTransformer
{
    public function transform(Wave $wave): array
    {
        return [
            'id' => $wave->getId(),
            'title' => $wave->getTitle(),
        ];
    }
}
