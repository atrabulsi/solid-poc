<?php

namespace App\Core\DDD\Application\CreateWave;

use App\Core\DDD\Application\Exceptions\ApplicationException;
use App\Core\DDD\Application\Exceptions\InputException;
use App\Core\DDD\Application\Transformers\WaveTransformerInterface;
use App\Core\DDD\Domain\Exceptions\StartDateMustBeBeforeEndDate;
use App\Core\DDD\Domain\Interfaces\WaveRepositoryInterface;
use App\Core\DDD\Domain\Models\Wave;
use App\Core\DDD\Domain\Models\WaveStatus;
use App\Core\DDD\Infrastructure\Exceptions\InfrastructureException;

class CreateWaveHandler
{
    private WaveRepositoryInterface $repository;
    public function __construct(WaveRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param CreateWaveCommand $command
     * @param WaveTransformerInterface $transformer
     * @return WaveTransformerInterface
     * @throws ApplicationException
     * @throws InputException
     */
    public function handle(CreateWaveCommand $command, WaveTransformerInterface $transformer): WaveTransformerInterface
    {
        try {
            $wave = new Wave(
                null,
                $command->getWaveName(),
                $command->getStartDate(),
                $command->getEndDate(),
                WaveStatus::draft()
            );
            $this->repository->createWave($wave);
            $transformer->write($wave);
            return $transformer;
        } catch (StartDateMustBeBeforeEndDate $e) {
            throw new InputException($e->getMessage());
        } catch (InfrastructureException $e) {
            throw new ApplicationException($e->getMessage());
        }
    }
}
