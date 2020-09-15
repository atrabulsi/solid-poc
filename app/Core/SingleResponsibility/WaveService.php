<?php

namespace App\Core\SingleResponsibility;

class WaveService
{
    private WaveRepository $repository;

    public function __construct(WaveRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getWaveById(int $id): ?Wave
    {
        return $this->repository->getWaveById($id);
    }
}
