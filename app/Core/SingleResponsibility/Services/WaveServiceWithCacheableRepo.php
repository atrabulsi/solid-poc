<?php

namespace App\Core\SingleResponsibility\Services;

use App\Core\SingleResponsibility\Repositories\WaveCacheableRepository;
use App\Core\SingleResponsibility\Transformers\WaveTransformer;

class WaveServiceWithCacheableRepo
{
    private WaveCacheableRepository $repository;
    private WaveTransformer $transformer;

    public function __construct(WaveCacheableRepository $repository, WaveTransformer $transformer)
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
