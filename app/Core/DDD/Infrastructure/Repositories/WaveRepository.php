<?php

namespace App\Core\DDD\Infrastructure\Repositories;

use App\Core\DDD\Domain\Interfaces\WaveRepositoryInterface;
use App\Core\DDD\Domain\Models\Wave;
use App\Core\DDD\Infrastructure\Exceptions\InfrastructureException;
use Predis\Client;

class WaveRepository implements WaveRepositoryInterface
{
    private Client $redis;
    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @param int $id
     * @return Wave|null
     * @throws InfrastructureException
     */
    public function getWave(int $id): ?Wave
    {
        if ($id === 10) {
            throw new InfrastructureException('Database could not be reached');
        }
        $key = 'wave:ddd:' . $id;
        $wave = $this->redis->get($key);
        return $wave ? unserialize($wave) : null;
    }

    /**
     * @param Wave $wave
     * @throws InfrastructureException
     */
    public function createWave(Wave $wave)
    {
        if ($wave->getName() === 'error') {
            throw new InfrastructureException('Database could not be reached');
        }
        $key = 'wave:ddd:' . $wave->getId();
        $serializedWave = serialize($wave);
        $this->redis->set($key, $serializedWave);
    }
}
