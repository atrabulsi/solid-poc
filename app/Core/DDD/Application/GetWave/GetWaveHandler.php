<?php

namespace App\Core\DDD\Application\GetWave;

use App\Core\DDD\Application\Exceptions\ApplicationException;
use App\Core\DDD\Application\Exceptions\InputException;
use App\Core\DDD\Application\Transformers\WaveTransformerInterface;
use App\Core\DDD\Domain\Interfaces\WaveRepositoryInterface;
use App\Core\DDD\Infrastructure\Exceptions\InfrastructureException;
use Illuminate\Http\Response;

class GetWaveHandler
{
    private WaveRepositoryInterface $repository;
    public function __construct(WaveRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param GetWaveQuery $query
     * @param WaveTransformerInterface $transformer
     * @return WaveTransformerInterface
     * @throws ApplicationException
     * @throws InputException
     */
    public function handle(GetWaveQuery $query, WaveTransformerInterface $transformer): WaveTransformerInterface
    {
        try {
            $wave = $this->repository->getWave($query->getId());
            if (!$wave) {
                throw new InputException('Wave not found', null, null, Response::HTTP_NOT_FOUND);
            }
            $transformer->write($wave);
            return $transformer;
        } catch (InfrastructureException $e) {
            throw new ApplicationException($e->getMessage());
        }
    }
}
