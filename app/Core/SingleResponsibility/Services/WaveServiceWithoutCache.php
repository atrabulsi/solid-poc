<?php

namespace App\Core\SingleResponsibility\Services;

use App\Core\SingleResponsibility\Repositories\WaveRepository;
use App\Core\SingleResponsibility\Transformers\WaveTransformer;

class WaveServiceWithoutCache
{
    private WaveRepository $repository;
    private WaveTransformer $transformer;

    public function __construct(WaveRepository $repository, WaveTransformer $transformer)
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
