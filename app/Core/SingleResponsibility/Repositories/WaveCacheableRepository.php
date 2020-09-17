<?php

namespace App\Core\SingleResponsibility\Repositories;

use App\Core\SingleResponsibility\Entities\Wave;
use Predis\Client;

class WaveCacheableRepository
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
        $key = 'wave:sr:' . $id;
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
