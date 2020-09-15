<?php

namespace App\Core\SingleResponsibility\Services;

use App\Core\SingleResponsibility\Repositories\WaveRepository;
use App\Core\SingleResponsibility\Transformers\WaveTransformer;
use Predis\Client;

class WaveServiceWithCaching
{
    private const DEFAULT_TIMEOUT = 3600;

    private Client $cache;
    private WaveRepository $repository;
    private WaveTransformer $transformer;

    public function __construct(WaveRepository $repository, WaveTransformer $transformer, Client $cache)
    {
        $this->repository = $repository;
        $this->transformer = $transformer;
        $this->cache = $cache;
    }

    public function getWaveById(int $id): ?array
    {
        $key = 'wave:' . $id;
        $wave = $this->cache->get($key);
        if ($wave) {
            $wave = unserialize($wave);
        } else {
            $wave = $this->repository->getWaveById($id);
            $serializedWave = serialize($wave);
            $this->cache->set($key, $serializedWave, 'ex', self::DEFAULT_TIMEOUT);
        }
        return $wave ? $this->transformer->transform($wave) : null;
    }
}
