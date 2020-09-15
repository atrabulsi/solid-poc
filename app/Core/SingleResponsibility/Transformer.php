<?php

namespace App\Core\SingleResponsibility;

class Transformer
{
    public function transform(Wave $wave): array
    {
        return [
            'id' => $wave->getId(),
            'title' => $wave->getTitle(),
        ];
    }
}
