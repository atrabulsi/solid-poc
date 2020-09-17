<?php

namespace App\Core\DependencyInversion\Repositories;

use App\Core\DependencyInversion\Entities\Wave;
use App\Core\DependencyInversion\Interfaces\WaveRepositoryInterface;
use Predis\Client;

class WaveCacheableRepository implements WaveRepositoryInterface
{
    private const DEFAULT_TIMEOUT = 3600;

    private WaveRepository $waveRepository;
    private Client $cache;

    public function __construct(WaveRepository $waveRepository, Client $cache)
    {
        $this->waveRepository = $waveRepository;
        $this->cache = $cache;
    }

    public function getWaveById(int $id): ?Wave
    {
        $key = 'wave:' . $id;
        $wave = $this->cache->get($key);
        if ($wave) {
            $wave = unserialize($wave);
        } else {
            $wave = $this->waveRepository->getWaveById($id);
            $serializedWave = serialize($wave);
            $this->cache->set($key, $serializedWave, 'ex', self::DEFAULT_TIMEOUT);
        }
        return $wave;
    }
}

