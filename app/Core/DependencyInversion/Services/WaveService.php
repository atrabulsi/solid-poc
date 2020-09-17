<?php

namespace App\Core\DependencyInversion\Services;

use App\Core\DependencyInversion\Interfaces\WaveRepositoryInterface;
use App\Core\DependencyInversion\Interfaces\WaveTransformerInterface;

class WaveService
{
    private WaveRepositoryInterface  $repository;

    public function __construct(WaveRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getWaveById(int $id, WaveTransformerInterface $transformer): ?WaveTransformerInterface
    {
        $wave = $this->repository->getWaveById($id);
        if (!$wave) {
            return null;
        }
        $transformer->write($wave);
        return $transformer;
    }
}
