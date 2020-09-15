<?php

namespace App\Core\SingleResponsibility;

class WaveService
{
    private WaveRepository $repository;
    private Transformer $transformer;

    public function __construct(WaveRepository $repository, Transformer $transformer)
    {
        $this->repository = $repository;
        $this->transformer = $transformer;
    }

    public function getWaveById(int $id): ?array
    {
        $wave = $this->repository->getWaveById($id);
        return $wave ? $this->transformer->transform($wave) : null;
    }
}
